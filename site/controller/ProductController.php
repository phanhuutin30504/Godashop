<?php
class ProductController
{
    // trang sản phẩm
    function index()
    {
        $conds = [];
        $sorts = [];
        $page = $_GET['page'] ?? 1;
        $item_per_page = 10;

        // Tìm sản phẩm theo category
        $category_id = $_GET['category_id'] ?? null;
        if ($category_id) {
            $conds = [
                'category_id' => [
                    'type' => '=',
                    'val' => $category_id //3
                ]
            ];
            // SELECT * FROM view_product WHERE category_id=3
        }

        // Tìm sản phẩm theo khoảng giá
        // price-range=100000-200000
        $priceRange = $_GET['price-range'] ?? null;
        if ($priceRange) {
            $temp = explode('-', $priceRange);
            $startPrice = $temp[0];
            $endPrice = $temp[1];
            $conds = [
                'sale_price' => [
                    'type' => 'BETWEEN',
                    'val' => "$startPrice AND $endPrice"
                ]
            ];
            // SELECT * FROM view_product WHERE sale_price BETWEEN 100000 AND 200000

            if ($endPrice == 'greater') {
                $conds = [
                    'sale_price' => [
                        'type' => '>=',
                        'val' => $startPrice
                    ]
                ];
            }
        }


        // sắp xếp
        // sort=alpha-desc
        $sort = $_GET['sort'] ?? null;
        if ($sort) {
            $temp = explode('-', $sort);
            $dummyColName = $temp[0];
            $order = strtoupper($temp[1]); // desc ->DESC

            // bảng ánh xạ tên cột trong database
            $map = ['alpha' => 'name', 'price' => 'sale_price', 'created' => 'created_date'];
            $colName = $map[$dummyColName];


            $sorts = [$colName => $order];



            // $sorts = ['name' =>'DESC']
            // Select * from view_product ORDER BY name DESC
        }

        $productRepository = new ProductRepository();
        $products = $productRepository->getBy($conds, $sorts, $page, $item_per_page);


        // lấy tất cả sản phẩm không phân trang
        $totalProducts = $productRepository->getBy($conds, $sorts);

        // Lấy tất cả danh mục từ db
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();

        // phân trang
        // ceil là hàm làm tròn lên: ceil(24/10) ->23
        $totalPage = ceil(count($totalProducts) / $item_per_page);


        require 'view/product/index.php';
    }
    function detail()
    {
        $sorts = [];
        $id = $_GET['id'];
        $productRepository = new ProductRepository();
        $product = $productRepository->find($id);
        // Lấy tất cả danh mục từ db
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();

        $category_id = $product->getCategoryId();

        $conds = [
            'category_id' => [
                'type' => '=',
                'val' => $category_id //3
            ],
            'id' => [
                'type' => '!=',
                'val' => $id //5
            ]

        ];


        //

        $relatedProducts = $productRepository->getBy($conds, []);
        require 'view/product/detail.php';
    }
    function storeComment()
    {
        $data = [];


        $data["email"] = $_POST['email'];
        $data["fullname"] = $_POST['fullname'];
        $data["star"] = $_POST['rating'];
        $data["created_date"] = date('Y-m-d H:i:s');
        $data["description"] = $_POST['description'];
        $data["product_id"] = $_POST['product_id'];
        $commentRepository = new CommentRepository();
        $commentRepository->save($data);

        $productRepository = new ProductRepository();
        $product = $productRepository->find($data["product_id"]);
         require 'view/product/comments.php';
    }
}