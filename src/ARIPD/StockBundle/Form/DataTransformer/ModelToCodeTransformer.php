<?php
namespace ARIPD\StockBundle\Form\DataTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use ARIPD\StockBundle\Entity\Incoming;

class ModelToCodeTransformer implements DataTransformerInterface {

	/**
	 * @var ObjectManager
	 */
	private $om;

	/**
	 * @param ObjectManager $om
	 */
	public function __construct(ObjectManager $om) {
		$this->om = $om;
	}

	/**
	 * Transforms an object (model) to a string (code).
	 *
	 * @param  Model|null $model
	 * @return string
	 */
	public function transform($model) {
		if (null === $model) {
			return "";
		}

		return $model->getCode();
	}

	/**
	 * Transforms a string (code) to an object (model).
	 *
	 * @param  string $code
	 * @return Model|null
	 * @throws TransformationFailedException if object (model) is not found.
	 */
	public function reverseTransform($code) {
		if (!$code) {
			return null;
		}

		$model = $this->om->getRepository('ARIPDStoreBundle:Model')
				->findOneBy(array('code' => $code));

		if (null === $model) {
			throw new TransformationFailedException(
					sprintf('A model with code "%s" does not exist!', $code));
		}

		return $model;
	}

}
