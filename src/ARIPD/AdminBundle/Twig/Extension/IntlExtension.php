<?php
/**
 * https://github.com/Garfield-fr/Twig-extensions/blob/master/lib/Twig/Extensions/Extension/Intl.php
 */

namespace ARIPD\AdminBundle\Twig\Extension;

use Twig_Extension;
use Twig_Filter_Function;
use Twig_Filter_Method;
use IntlDateFormatter;
use NumberFormatter;
use Locale;
use DateTime;
use DateTimeZone;
use RuntimeException;

class IntlExtension extends Twig_Extension
{
	public function __construct()
	{
		if (!class_exists('IntlDateFormatter') || !class_exists('NumberFormatter')) {
			throw new RuntimeException('The intl extension is needed to use intl-based filters.');
		}
	}
	
	/**
	 * Returns a list of filters to add to the existing list.
	 *
	 * @return array An array of filters
	 */
	public function getFilters()
	{
		return array(
				'localizeddate'     => new Twig_Filter_Method($this, 'twig_localized_date_filter'),
				'localizednumber'   => new Twig_Filter_Method($this, 'twig_localized_number_filter'),
				'localizedcurrency' => new Twig_Filter_Method($this, 'twig_localized_currency_filter'),
				'localizedlanguage' => new Twig_Filter_Method($this, 'twig_localized_language_filter'),
		);
	}
	
	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'intl_extension';
	}
	
	function twig_localized_date_filter($date, $dateFormat = 'medium', $timeFormat = 'medium', $locale = null)
	{
		$formatValues = array(
				'none'   => IntlDateFormatter::NONE,
				'short'  => IntlDateFormatter::SHORT,
				'medium' => IntlDateFormatter::MEDIUM,
				'long'   => IntlDateFormatter::LONG,
				'full'   => IntlDateFormatter::FULL,
		);
	
		$formatter = IntlDateFormatter::create(
				$locale !== null ? $locale : Locale::getDefault(),
				$formatValues[$dateFormat],
				$formatValues[$timeFormat]
		);
	
		if (!$date instanceof DateTime) {
			if (ctype_digit((string) $date)) {
				$date = new DateTime('@'.$date);
				$date->setTimezone(new DateTimeZone(date_default_timezone_get()));
			} else {
				$date = new DateTime($date);
			}
		}
	
		return $formatter->format($date->getTimestamp());
	}
	
	function twig_localized_number_filter($number, $style = 'decimal', $format = 'default', $currency = null, $locale = null )
	{
		$formatter = $this->twig_get_number_formatter(
				$locale !== null ? $locale : Locale::getDefault(),
				$style
		);
	
		$formatValues = array(
				'default'   => NumberFormatter::TYPE_DEFAULT,
				'int32'     => NumberFormatter::TYPE_INT32,
				'int64'     => NumberFormatter::TYPE_INT64,
				'double'    => NumberFormatter::TYPE_DOUBLE,
				'currency'  => NumberFormatter::TYPE_CURRENCY,
		);
	
		return $formatter->format(
				$number,
				$formatValues[$format]);
	}

	function twig_localized_currency_filter($number, $currency = null, $locale = null)
	{
		$formatter = $this->twig_get_number_formatter(
				$locale !== null ? $locale : Locale::getDefault(),
				'currency'
		);
	
		return $formatter->formatCurrency($number, $currency);
	}
	
	function twig_get_number_formatter($locale, $style)
	{
		$styleValues = array(
				'decimal'       => NumberFormatter::DECIMAL,
				'currency'      => NumberFormatter::CURRENCY,
				'percent'       => NumberFormatter::PERCENT,
				'scientific'    => NumberFormatter::SCIENTIFIC,
				'spellout'      => NumberFormatter::SPELLOUT,
				'ordinal'       => NumberFormatter::ORDINAL,
				'duration'      => NumberFormatter::DURATION,
		);
	
		return NumberFormatter::create(
				$locale !== null ? $locale : Locale::getDefault(),
				$styleValues[$style]
		);
	}
	
	function twig_localized_language_filter($locale = null)
	{
		return Locale::getDisplayLanguage($locale, $locale);
	}
	
}