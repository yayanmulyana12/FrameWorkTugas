<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use Illuminate\Http\Request;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\PdfToImage\Pdf as PdfToImage;
use Illuminate\Support\Facades\Storage;
use App\Models\Supplier;



class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Membuat query builder baru untuk model Product
        $query = Product::with('supplier');

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                    ->orWhere('unit', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('information', 'like', "%{$search}%")
                    ->orWhere('producer', 'like', "%{$search}%");
            });
        }

        // Sorting dinamis
        $allowedSorts = ['product_name', 'unit', 'type', 'information', 'qty', 'producer'];
        $sort = $request->get('sort', 'product_name');      // kolom default
        $direction = $request->get('direction', 'asc');     // arah default

        // Pastikan kolom valid
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'product_name';
        }

        // Urutkan berdasarkan kolom & arah yang dipilih
        $query->orderBy($sort, $direction);

        // Pagination (2 data per halaman)
        $data = $query->paginate(2)->appends([
            'search' => $request->search,
            'sort' => $sort,
            'direction' => $direction
        ]);        // Kirim ke view
        return view("master-data.product-master.index-product", compact("data", "sort", "direction"));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = \App\Models\Supplier::all();
        return view("master-data.product-master.create-product", compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validasi_data = $request->validate([
            'product_name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'information' => 'nullable|string',
            'qty' => 'required|integer',
            'producer' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id', // foreign key valid
        ]);

        try {

            Product::create($validasi_data);

            return redirect()
                ->route('product-index')
                ->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view("master-data.product-master.detail-product", data: compact(var_name: 'product'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $suppliers = Supplier::all(); // tambahkan baris ini
        return view('master-data.product-master.edit-product', compact('product', 'suppliers'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'information' => 'nullable|string',
            'qty' => 'required|integer|min:1',
            'producer' => 'required|string|max:255',
        ]);

        try {
            $product = Product::findOrFail($id);

            $product->update([
                'product_name' => $request->product_name,
                'unit' => $request->unit,
                'type' => $request->type,
                'information' => $request->information,
                'qty' => $request->qty,
                'producer' => $request->producer,
            ]);

            // Jika berhasil → redirect ke halaman index
            return redirect()
                ->route('product-index')
                ->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            // Jika error → kembali ke halaman sebelumnya dengan pesan error
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return redirect()->back()->with('success', 'Product Udh Dihapus');

        }
        return redirect()->back()->with('error', 'Product nya ga ada');
    }
    public function exportExcel(): BinaryFileResponse
    {
        return Excel::download(new ProductsExport, 'product.xlsx');
    }

    public function exportPDF()
    {
        $products = \App\Models\Product::all();

        $startPeriod = \App\Models\Product::min('created_at')
            ? date('d F Y', strtotime(\App\Models\Product::min('created_at')))
            : now()->format('d F Y');
        $endPeriod = now()->format('d F Y');

        $pdf = Pdf::loadView('master-data.product-master.export-pdf', compact('products', 'startPeriod', 'endPeriod'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('product.pdf');
    }
    public function exportJPG()
    {
        $products = \App\Models\Product::all();
        $startPeriod = \App\Models\Product::min('created_at');
        $endPeriod = \App\Models\Product::max('created_at');

        $pdf = Pdf::loadView('master-data.product-master.export-pdf', compact('products', 'startPeriod', 'endPeriod'));
        $pdfPath = storage_path('app/public/temp_product.pdf');
        $jpgPath = storage_path('app/public/product.jpg');
        $pdf->save($pdfPath);


        $command = "gswin64c -sDEVICE=jpeg -dNOPAUSE -dBATCH -sOutputFile=\"{$jpgPath}\" \"{$pdfPath}\"";
        exec($command);

        return response()->download($jpgPath)->deleteFileAfterSend(true);
    }

}
