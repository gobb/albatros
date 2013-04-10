<?php
namespace ARIPD\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ARIPD\StoreBundle\Entity\Product;

class WebserviceController extends Controller {
	public function testAction() {
		$file = 'bundles/aripddefault/upload/logo_read.xml';
		if (file_exists($file)) {
			$xmlstr = simplexml_load_file($file);

			$user = $this->get("security.context")->getToken()->getUser();
			$em = $this->getDoctrine()->getManager();

			foreach ($xmlstr->set as $item) {
				$product = $em->getRepository('ARIPDStoreBundle:Product')
						->findBy(array("code" => $item->attributes()->CODE));

				if (!$product) {
					$product = new Product();
				}

				$product->setUser($user);
				$product->setCode($item->attributes()->CODE);
				$product->setName($item->attributes()->NAME);
				$product->setPrice($item->attributes()->PRICE);
				$product->setDescription($item->attributes()->NAME);
				$em->persist($product);
			}

			$em->flush();
			exit('Completed');
		} else {
			exit('Failed to open ' . $file);
		}

		return new Response();
	}
}
