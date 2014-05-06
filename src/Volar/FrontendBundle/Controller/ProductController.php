<?php

namespace Volar\FrontendBundle\Controller;

use Volar\FrontendBundle\Entity\Product;
use Volar\FrontendBundle\Entity\ProductImage;
use Volar\FrontendBundle\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Volar\FrontendBundle\Form\Type\ImageType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Dumper\YamlFileDumper;
use Symfony\Component\Translation\MessageCatalogue;

//¿?
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductController extends Controller
{

    //Stores the name of the products buyed
    //to send to the TPV system
    private $products;
    private $password='7965U383C620LLQ2';  //Producción

	public function createAction(Request $request){
		return $this->getForm($request, "create");
	}

	public function deleteAction($id){
		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('FrontendBundle:Product')->find($id);
		
		if (!$product) {
			throw $this->createNotFoundException('No product found for id '.$id);
		}

		$em->remove($product);
		$em->flush();

		return $this->redirect($this->generateUrl('_adminProducts'));
	}

	public function showAction($id){
		$product = $this->getDoctrine()
			->getRepository('FrontendBundle:Product')
			->find($id);

		if (!$product) {
			throw $this->createNotFoundException('No product found for id '.$id);
        }
        $aux = $product->getImages();
        foreach ( $aux as $image ){
            $images[$product->getId()] = $image->getWebPath();
        }

        return $this->render('FrontendBundle:Shop:detail.html.twig', array(
            "product" => $product,
            "images"  => $images,
            "page"    => "products",
        ));
	}
	
	public function showAdminAction($id){
		$product = $this->getDoctrine()
			->getRepository('FrontendBundle:Product')
			->find($id);

		if (!$product) {
			throw $this->createNotFoundException('No product found for id '.$id);
        }
        $aux = $product->getImages();
        foreach ( $aux as $image ){
            $images[$product->getId()] = $image->getWebPath();
        }

        return $this->render('FrontendBundle:Shop:detailAdmin.html.twig', array(
            "product" => $product,
            "images"  => $images,
            "page"    => "products",
        ));
	}

    private function isAdmin(){
        $request = $this->container->get('request');
        if (strpos($request->getPathInfo(),"admin"))
            return 1;
        return 0;
    }

    //Get a list of products for the admin
	public function listAdminAction(){
		$em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('FrontendBundle:Product')->findAll();
        $images = array();
        foreach ($products as $i => $product){
            $aux = $product->getImages();
            foreach ( $aux as $image ){
                $images[$product->getId()] = $image->getWebPath();
            }
        }    

		return $this->render('FrontendBundle:Shop:listAdmin.html.twig', array(
            "products"  => $products,
            "images"    => $images,
           ));
	}

    //Get a list of products for normal users
	public function listAction(){
        $session = $this->getRequest()->getSession();
        if (!$session->isStarted())
            $session->start();
        $session->clear();

		$em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('FrontendBundle:Product')->findAll();
        $images = array();
        $urlMerchant="http://test.volarenparapente.com/web/app_dev.php/bank/check";
        foreach ($products as $i => $product){
            $aux = $product->getImages();
            foreach ( $aux as $image ){
                $images[$product->getId()] = $image->getWebPath();
            }
        }    

        switch ($this->getRequest()->getLocale()) {
            case "es":
                $lang=1;
                break;
            case "ca":
                $lang=3;
                break;
            case "en":
                $lang=2;
                break;
            default:
                $lang=1;
                break;
        }

		return $this->render('FrontendBundle:Shop:list.html.twig', array(
            "products"  => $products,
            "images"    => $images,
            "page"      => "products",
            "total"     => $this->getTotalPrice(),
            "action"    => "",
            "orderNumber" => "",
            "merchantAmount" => "",
            "description" => "",
            "code"      => "",
            "transactionType" => "",
            "signature" => "",
            "currency"  => "",
            "terminal"  => 1,
            "urlMerchant" => "",
            "lang"      => $lang,
           ));
	}
    
	public function updateAction(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('FrontendBundle:Product')->find($id);
		
		if (!$product) {
			throw $this->createNotFoundException('No product found for id '.$id);
		}

		return $this->getForm($request,"update",$product);
	}

	/**
	 *
	 * */
	public function getForm($request,$action="create",$product=null){
        if($product == null){
		    $product = new Product();
            $product->addImage(new ProductImage());
        }
        $message = "";
        $nameES = "";
        $shortES = "";
        $longES = "";
        $nameEN = "";
        $shortEN = "";
        $longEN = "";
        $nameCA = "";
        $shortCA = "";
        $longCA = "";

        if($action == "update"){
            $id = $product->getId();
            $translator = $this->get("translator");
            $nameES = $translator->trans("product.$id.name",Array(),"products","es");
            $shortES = $translator->trans("product.$id.shortDescription",Array(),"products","es");
            $longES = $translator->trans("product.$id.longDescription",Array(),"products","es");
            $nameEN = $translator->trans("product.$id.name",Array(),"products","en");
            $shortEN = $translator->trans("product.$id.shortDescription",Array(),"products","en");
            $longEN = $translator->trans("product.$id.longDescription",Array(),"products","en");
            $nameCA = $translator->trans("product.$id.name",Array(),"products","ca");
            $shortCA = $translator->trans("product.$id.shortDescription",Array(),"products","ca");
            $longCA = $translator->trans("product.$id.longDescription",Array(),"products","ca");
        }

		$form = $this->createFormBuilder($product)
			->add("name-es", "text", array(
                "mapped" => false,
                "label" => $this->get("translator")->trans("Shop.Forms.Product.nameES"),
                "data" => $nameES,
			))
			->add("name-en", "text", array(
                "mapped" => false,
				"label" => $this->get("translator")->trans("Shop.Forms.Product.nameEN"),
                "data" => $nameEN,
			))
			->add("name-ca", "text", array(
                "mapped" => false,
                "label" => $this->get("translator")->trans("Shop.Forms.Product.nameCA"),
                "data" => $nameCA,
			))
			->add("shortDescription-es", "textarea", array(
                "label" => $this->get("translator")->trans("Shop.Forms.Product.shortDescriptionES"),
                "mapped" => false,
                "data" => $shortES,
                "attr" => array(
                    "class" => "tinymce",
                ),
			))
			->add("shortDescription-en", "textarea", array(
				"label" => $this->get("translator")->trans("Shop.Forms.Product.shortDescriptionEN"),
                "mapped" => false,
                "data" => $shortEN,
                "attr" => array(
                    "class" => "tinymce",
                ),
			))            
			->add("shortDescription-ca", "textarea", array(
				"label" => $this->get("translator")->trans("Shop.Forms.Product.shortDescriptionCA"),
                "mapped" => false,
                "data" => $shortCA,
                "attr" => array(
                    "class" => "tinymce",
                ),
			))            
			->add("longDescription-es", "textarea", array(
				"label" => $this->get("translator")->trans("Shop.Forms.Product.longDescriptionES"),
                "mapped" => false,
                "data" => $longES,
                "attr" => array(
                    "class" => "tinymce",
                ),
			))
			->add("longDescription-en", "textarea", array(
				"label" => $this->get("translator")->trans("Shop.Forms.Product.longDescriptionEN"),
                "mapped" => false,
                "data" => $longEN,
                "attr" => array(
                    "class" => "tinymce",
                ),
			))
			->add("longDescription-ca", "textarea", array(
				"label" => $this->get("translator")->trans("Shop.Forms.Product.longDescriptionCA"),
                "mapped" => false,
                "data" => $longCA,
                "attr" => array(
                    "class" => "tinymce",
                ),
			))
			->add("price", "money", array(
				"label" => $this->get("translator")->trans("Shop.Forms.Product.price")
            ))
            ->add("images", "collection", array(
                "type" => new ImageType(),
                "label" => false,
                "options" => array(
                    "label" => $this->get("translator")->trans("Shop.Forms.Product.image"),
                )
            ))
            ->getForm();


		if ($request->isMethod("POST")){
            $form->bind($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                foreach ($product->getImages() as $image){
                    $image->preUpload();
                    $image->upload();
                    $image->setProduct($product);
                    $em->persist($image);
                }
                $em->flush();

                $locales = Array("en", "ca", "es");
                $dumper = new YamlFileDumper();
                $loader = new YamlFileLoader();
                $domain="products";
                foreach ($locales as $locale){
                    $file=__DIR__."/../Resources/translations/products.$locale.yml";
                    
                    if(file_exists($file)){
                        $catalogue = $loader->load($file,$locale,$domain);
                    }else{
                        $catalogue = new MessageCatalogue($locale);
                    }
                    $catalogue->set("product.".$product->getId().".name", $form["name-".$locale]->getData(), "products");
                    $catalogue->set("product.".$product->getId().".shortDescription", $form["shortDescription-".$locale]->getData(), "products");
                    $catalogue->set("product.".$product->getId().".longDescription", $form["longDescription-".$locale]->getData(), "products");
                    $dumper->dump($catalogue,array("path" => __DIR__."/../Resources/translations/"));
                }
                $message = $this->get("translator")->trans("Shop.Forms.Product.added");
            }
		}

		return $this->render('FrontendBundle:Shop:product.html.twig', array(
            'form'=>$form->createView(),
            'message'=>$message,
            'action'=>$action,
            "page"    => "products",
		));
	}

    //Cart functions (Buy system)


    //Adds a product to the cart
    public function addToCartAction(Request $request, $id){
        $session = $this->getRequest()->getSession();
        if (!$session->isStarted())
            $session->start();

        $quantity=$session->get("product_$id")+1;
        $session->set("product_$id", $quantity);
        $response = Array("total" => $this->getTotalPrice(), "quantity" => $quantity, "id" => $id);
        return new Response(json_encode($response));
    }

    //Removes a product from the cart
    public function delFromCartAction(Request $request, $id){
        $session = $this->getRequest()->getSession();
        if (!$session->isStarted())
            $session->start();

        $quantity=$session->get("product_$id")-1;
        if($quantity < 0){
            $quantity=0;
        }
        $session->set("product_$id", $quantity);
        $response = Array("total" => $this->getTotalPrice(), "quantity" => $quantity, "id" => $id);
        return new Response(json_encode($response));
    }
    

    //Gets the total price of the products
    //in the cart
    private function getTotalPrice(){
        $session = $this->getRequest()->getSession();
        if (!$session->isStarted())
            $session->start();

        $products=$session->all();
        $total=0;
        $this->products="";
        foreach ($products as $key => $quantity) {
            if (strpos($key,"product_") !== FALSE){
                $id=str_replace("product_","",$key);
                $product = $this->getDoctrine()
			            ->getRepository('FrontendBundle:Product')
                        ->find($id);
                if ( ! is_null($product) ){
                  $total+=($product->getPrice()*$quantity);
                    $this->products .= $this->get("translator")->trans("product.".$product->getId().".name", Array(), "products").", ";
                }
            }
        }
        $this->products = rtrim($this->products,", ");
        return $total;
    }

    //Generates the order number
    private function getBankOrderNumber(){
        //Generates the order number to be sent to the bank
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	    $string_length = 12;
	    $randomstring = "";

        for ($i=0; $i<$string_length; $i++) {
            if ($i<4){
                $randomstring .= rand(0,9);
            }else{
                $randomstring .= $chars[rand(0,strlen($chars)-1)];
            }
        } 

        return $randomstring;
    }

    private function alphaID($to_num = false, $pad_up = 5, $passKey = "sjdfl·89=)//YUYIkhkllj,n,,bb--.-_MM;BXAWÇÑÇÇnbçñḉ")
    {
      $in = time()+rand();
      $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      if ($passKey !== null) {
          // Although this function's purpose is to just make the
          // ID short - and not so much secure,
          // with this patch by Simon Franz (http://blog.snaky.org/)
          // you can optionally supply a password to make it harder
          // to calculate the corresponding numeric ID

          for ($n = 0; $n<strlen($index); $n++) {
              $i[] = substr( $index,$n ,1);
          }

          $passhash = hash('sha256',$passKey);
          $passhash = (strlen($passhash) < strlen($index))
              ? hash('sha512',$passKey)
              : $passhash;

          for ($n=0; $n < strlen($index); $n++) {
              $p[] =  substr($passhash, $n ,1);
          }

          array_multisort($p,  SORT_DESC, $i);
          $index = implode($i);
      }

      $base  = strlen($index);

      if ($to_num) {
          // Digital number  <<--  alphabet letter code
          $in  = strrev($in);
          $out = 0;
          $len = strlen($in) - 1;
          for ($t = 0; $t <= $len; $t++) {
              $bcpow = bcpow($base, $len - $t);
              $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
          }

          if (is_numeric($pad_up)) {
              $pad_up--;
              if ($pad_up > 0) {
                  $out -= pow($base, $pad_up);
              }
          }
          $out = sprintf('%F', $out);
          $out = substr($out, 0, strpos($out, '.'));
      } else {
          // Digital number  -->>  alphabet letter code
          if (is_numeric($pad_up)) {
              $pad_up--;
              if ($pad_up > 0) {
                  $in += pow($base, $pad_up);
              }
          }

          $out = "";
          for ($t = floor(log($in, $base)); $t >= 0; $t--) {
              $bcp = bcpow($base, $t);
              $a   = floor($in / $bcp) % $base;
              $out = $out . substr($index, $a, 1);
              $in  = $in - ($a * $bcp);
          }
          $out = strrev($out); // reverse
      }

      return $out;
    }
    
    //Proces the products in the cart
    public function buyAction(Request $request){
        $session = $this->getRequest()->getSession();
        if (!$session->isStarted())
            $session->start();

        $products=$session->all();
        $orderNumber=$this->getBankOrderNumber();
        $userNumber = $this->alphaID();
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        foreach ($products as $key => $quantity) {
            if (strpos($key,"product_") !== FALSE){
                if ($quantity > 0){
                    $order = new Orders();
                    $order->setOrderNumber($orderNumber);
                    $order->setUserOrderNumber($userNumber);
                    $order->setProductId(str_replace("product_","",$key));
                    $order->setQuantity($quantity);
                    $order->setStatus(0);
                    $order->setMail($data["mail"]);
                    $order->setName($data["name"]);
                    $order->setSurname($data["surname"]);
                    $order->setMailPresent($data["mailP"]);
                    $order->setNamePresent($data["nameP"]);
                    $order->setSurnamePresent($data["surnameP"]);
                    $order->setAnonymousPresent($data["anonymousP"]);
                    $order->setJoinDate($data["flyDate"]);
                    $em->persist($order);
                }
            }
        }
        $em->flush();

        $formUrl="https://sis.sermepa.es/sis/realizarPago"; //Producción
        if ( strpos($_SERVER["REQUEST_URI"], "app_dev.php") !== false ){
            $formUrl="https://sis-t.sermepa.es:25443/sis/realizarPago";  //Test
            $this->password="qwertyasdf0123456789"; //Test
        }
        $amount=$this->getTotalPrice();
        $merchantAmount=number_format($amount,2,"","");
        //$merchantAmount=$amount*100;
        $code=322674599;
        $transactionType=0;
        //$urlMerchant="http://test.volarenparapente.com/web/app_dev.php/bank/check";
        $urlMerchant=null;
        //$input = $amount.$orderNumber.$code."978".$transactionType.$urlMerchant.$this->password;
        $input = $merchantAmount.$orderNumber.$code."978".$transactionType.$this->password;

        $response = new Response(json_encode(Array(
            "formUrl" => $formUrl, 
            "currency" => 978, 
            "description" => $this->products,
            "code" => $code, 
            "transactionType" => $transactionType, 
            "urlMerchant" => '', 
            "merchantAmount" => $merchantAmount, 
            "signature" => sha1($input), 
            "merchatnOrder" => $orderNumber,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function checkFromBankAction(Request $request){
        $fd = fopen("transaction.txt","w");
        fwrite(var_dump($request->request->all()),$fd);
        fclose($fd);
    }

    public function testMessageAction(){
        $translator = $this->get("translator");
        
        $message = \Swift_Message::newInstance()
        ->setSubject($translator->trans("User.mail.subject"))
        ->setFrom('info@volarenparapente.com')
        ->setTo("abdulet@gmail.com")
        ->setBody(
            $this->renderView(
                'FrontendBundle:Shop:mail.html.twig',
                array(
                    'name' => "Abdul Pallarès",
                    'reference' => "ref:",
                    'products' => array(array("name" => "p1", "quantity"=>2, "price"=>"20")),
                    'total' => "100",
                    'headerProduct' => "Product",
                    'headerQuantity' => "Cantidad",
                    'headerPrice' => "Precio",
                    "present" => 1,
                    "fecha" => "22/10/2013",
                    "buyer" => "Mònica",
                )
            ), "text/html");

            require_once($this->get('kernel')->getRootDir().'/config/dompdf_config.inc.php');
            $dompdf = new \DOMPDF();
            $dompdf->load_html(
                $this->renderView(
                    'FrontendBundle:Shop:mail.html.twig',
                array(
                    'name' => "Abdul Pallarès",
                    'reference' => "wrF34",
                    'products' => array(array("name" => "Tandem corto", "quantity"=>1, "price"=>"20")),
                    'total' => "100",
                    'headerProduct' => "Producto",
                    'headerQuantity' => "Cantidad",
                    'headerPrice' => "Precio",
                    "present" => 1,
                    "buyer" => "Mònica te ha hecho un regalo increíble!!!",
                    "fecha" => "22/10/2013",
                )
                )
            );
            $dompdf->render();
            //var_dump($_dompdf_warnings);
            //exit();
            //header('Content-type: application/pdf');
            //echo $dompdf->output();
            //exit();
            $message->attach(\Swift_Attachment::newInstance($dompdf->output(), "test.pdf", "application/pdf"));
            $this->get('mailer')->send($message);
            return $this->render('FrontendBundle:Shop:ok.html.twig',Array(
                "page"=>"products",
                "errormsg" => "",
            ));
    }

    public function okAction(Request $request){
        $translator = $this->get("translator");
        $data = $request->query->all();
        if ( strpos($_SERVER["REQUEST_URI"], "app_dev.php") !== false )
            $this->password="qwertyasdf0123456789"; //Test
        $hash = strtoupper(sha1($data["Ds_Amount"].$data["Ds_Order"].$data["Ds_MerchantCode"].$data["Ds_Currency"].$data["Ds_Response"].$this->password));

        $errormsg="";
        if($data["Ds_Signature"] != $hash){
            $errormsg = "Error: 407";
            $errormsg = $data["Ds_Signature"].":::".$hash;
        }else{
            $em = $this->getDoctrine()->getManager();
            $order = $em->getRepository('FrontendBundle:Orders')->findByOrderNumber($data["Ds_Order"]);
            $total = $data["Ds_Amount"] / 100;
            $totalInDb=0;
            $products = Array();
            foreach ($order as $row){
                $product = $em->getRepository('FrontendBundle:Product')->find($row->getProductId());
                if( is_null($product) )
                    continue;
                $itemInfo = Array ("name" => $translator->trans("product.".$product->getId().".name",Array(),"products"), "quantity" => $row->getQuantity(), "price" => $product->getPrice() * $row->getQuantity());
                array_push($products, $itemInfo);
                $totalInDb += $product->getPrice() * $row->getQuantity();
            }

            if($total == $totalInDb){
                foreach ($order as $row){
                    $row->setStatus(1);
                    $row->setAuthorisationCode($data["Ds_AuthorisationCode"]);
                    $row->setDate($data["Ds_Date"]);
                    $row->setTime($data["Ds_Hour"]);
                    $row->setResponseCode($data["Ds_Response"]);
                    $em->persist($row);
                }
                $em->flush();
            }
        }

        $message = \Swift_Message::newInstance()
        ->setSubject($translator->trans("User.mail.subject"))
        ->setFrom('info@volarenparapente.com')
        ->setTo($order[0]->getMail())
        ->setBody(
            $this->renderView(
                'FrontendBundle:Shop:mail.html.twig',
                array(
                    'name' => $order[0]->getName(),
                    'reference' => $order[0]->getUserOrderNumber(),
                    'products' => $products,
                    'total' => $total,
                    'headerProduct' => $translator->trans("User.mail.product"),
                    'headerQuantity' => $translator->trans("User.mail.quantity"),
                    'headerPrice' => $translator->trans("User.mail.price"),
                    "present" => 0,
                    "fecha" => $order[0]->getJoinDate(),
                )
            ), "text/html");
        $message->addPart(
            $this->renderView(
                'FrontendBundle:Shop:mail.txt.twig',
                array(
                    'name' => $order[0]->getName(),
                    'reference' => $order[0]->getUserOrderNumber(),
                    'products' => $products,
                    'total' => $total,
                    'headerProduct' => $translator->trans("User.mail.product"),
                    'headerQuantity' => $translator->trans("User.mail.quantity"),
                    'headerPrice' => $translator->trans("User.mail.price"),
                    "present" => 0,
                    "fecha" => $order[0]->getJoinDate(),
                )
            ), "text/plain");
        $this->get('mailer')->send($message);

        if ( $order[0]->getMailPresent() != "" ){
            if ( $order[0]->getAnonymousPresent() == "true" ){
                $buyer = $translator->trans("Bono.mail.anonimo.html",Array(),"messages");
                $buyertxt = $translator->trans("Bono.mail.anonimo.txt",Array(),"messages");
            }else{
                $buyer = $translator->trans("Bono.mail.comprador",Array("%NAMEPRESENT%" => $order[0]->getName()),"messages");
                $buyertxt = $translator->trans("Bono.mail.comprador",Array(),"messages");
            }
            require_once($this->get('kernel')->getRootDir().'/config/dompdf_config.inc.php');
            $dompdf = new \DOMPDF();
            $dompdf->load_html(
                $this->renderView(
                    'FrontendBundle:Shop:mail.html.twig',
                    array(
                        'name' => $order[0]->getNamePresent(),
                        'reference' => $order[0]->getUserOrderNumber(),
                        'products' => $products,
                        'total' => $total,
                        'headerProduct' => $translator->trans("User.mail.product"),
                        'headerQuantity' => $translator->trans("User.mail.quantity"),
                        'headerPrice' => $translator->trans("User.mail.price"),
                        "present" => 1,
                        "buyer" => $buyer,
                        "fecha" => $order[0]->getJoinDate(),
                    )
                )
            );
            $dompdf->render();
            
            $message = \Swift_Message::newInstance()
            ->setSubject($translator->trans("Bono.mail.subject"))
            ->setFrom('info@volarenparapente.com')
            ->setTo($order[0]->getMailPresent())
            ->setBody(
                $this->renderView(
                    'FrontendBundle:Shop:mail.html.twig',
                    array(
                        'name' => $order[0]->getNamePresent(),
                        'reference' => $order[0]->getUserOrderNumber(),
                        'products' => $products,
                        'headerProduct' => $translator->trans("User.mail.product"),
                        'headerQuantity' => $translator->trans("User.mail.quantity"),
                        "present" => 1,
                        "buyer" => $buyer,
                        "fecha" => $order[0]->getJoinDate(),
                    )
                ), "text/html");
            $message->addPart(
                $this->renderView(
                    'FrontendBundle:Shop:mail.txt.twig',
                    array(
                        'name' => $order[0]->getNamePresent(),
                        'reference' => $order[0]->getUserOrderNumber(),
                        'products' => $products,
                        'headerProduct' => $translator->trans("User.mail.product"),
                        'headerQuantity' => $translator->trans("User.mail.quantity"),
                        "present" => 1,
                        "buyer" => $buyertxt,
                        "fecha" => $order[0]->getJoinDate(),
                    )
                ), "text/plain");
            $message->attach(\Swift_Attachment::newInstance($dompdf->output(), $order[0]->getUserOrderNumber()."pdf", "application/pdf"));

            $this->get('mailer')->send($message);
        }
        $session = $this->getRequest()->getSession();
        if (!$session->isStarted())
            $session->start();
        $session->clear();
        return $this->render('FrontendBundle:Shop:ok.html.twig',Array(
            "page"=>"products",
            "errormsg" => $errormsg,
        ));
    }

    public function koAction(Request $request){
        return $this->render('FrontendBundle:Shop:ko.html.twig',Array(
            "page"=>"products",
        ));
    }

    public function listOrdersAction(Request $request){
		$em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository('FrontendBundle:Orders')->findAll();
		return $this->render('FrontendBundle:Shop:listOrders.html.twig', array(
            "orders" => $orders,
        ));
    }
}
