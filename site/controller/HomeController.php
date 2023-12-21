<?php 
class HomeController{

    function index(){
        $conds =[];
        $page =1;
        $item_per_page =4;
        $sorts =['featured' => 'DESC'];
        $productRepository = new ProductRepository();
        $featuredProducts =$productRepository->getBy($conds,$sorts,$page,$item_per_page);

        $sorts =['created_date' =>'DESC'];
        $latestProducts =$productRepository->getBy($conds,$sorts,$page,$item_per_page);



        //lấy tất cả danh mục từ db
        $categoryReposity = new CategoryRepository();
        $categories =$categoryReposity->getAll();

        // cấu trúc dữ liệu truyền qua view
        $categoryProducts =[];
        foreach($categories as $category){
            $conds =[
                'category_id' =>[
                    'type' => '=',
                    'val' => $category->getId()
                ]
                ];
                //SELECT *FROM view_product WHERE category_id=3
                $products =$productRepository->getBy($conds,$sorts,$page,$item_per_page);

                //Thêm products này vào cấu trúc dữ liệu ở trên
                $categoryProducts[] =[
                    'categoryName' => $category->getName(),
                    'products' =>$products
                ];
        }
        require 'view/home/index.php';
    }
}
    
?>