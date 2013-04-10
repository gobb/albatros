<?php
namespace ARIPD\AdminBundle\Util;

use Goutte\Client;

use Symfony\Component\DomCrawler\Crawler;

class ARIPDString {
	
	/**
	 * TODO text içerisindeki resimlerin url adresini çeken method
	 */
	public static function getImagesFromString($html=null) {
		$html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
				<img class="image" src="https://ssl.gstatic.com/images/logos/google_logo_41.png" />
				<p class="message">Hello World!</p>
        <p class="cem">Hello Crawler!</p>
				<img class="image" src="http://l.yimg.com/dh/ap/default/120910/yahoo_logo_tr.png" />
    </body>
</html>
HTML;
		
		$crawler = new Crawler($html);
		$elements = $crawler->filterXPath('//img');
		//$elements = $crawler->filterXPath('//img[@class="image"]');
		if ($elements->count() > 0 ) {
			echo $elements->first()->attr('src');
		}
		else {
			echo "no image";
		}
		exit;
	}
	
	public static function getImagesFromPicasa($userId, $albumId) {
		$url = "https://picasaweb.google.com/data/feed/api/user/$userId/albumid/$albumId";
		
		$client = new Client();
		$crawler = $client->request('GET', $url);
		$attributes = $crawler->filterXpath('//entry/content')->extract(array('src', 'type'));
		return $attributes;
	}
	
	/**
	 * Convert object into array   NOT TESTED
	 */
	public function convert($data) {
		if(is_array($data) || is_object($data)) {
			$result = array();
			foreach ($data as $key => $value) {
				$result[$key] = $this->__convert($value);
			}
			return $result;
		}
		return $data;
	}
	
	/**
	 * doYouMean, Bunu mu demek istediniz
	 * http://aziz.furkanilgin.com/index.php/90-algorithms/83-levenshtein.html?showall=&start=3
	 */
	public static function doYouMean() {
		$aranan_kelime = 'ph'; //Bu değerin veritabanından geldiğini varsayalım
		$diziler = array('php', 'asp', '.net', 'jsp', 'java', 'javascript', 'html', 'c', 'css', 'xml');
		//$diziler dizisi, veritabanından çekilen birçok veri dizisi olduğu varsayalım
		$uzunluk = -1;
		
		foreach($diziler as $dizi) {
			$benzerlik = levenshtein($aranan_kelime, $dizi);
			if($benzerlik == 0) {
				$yakinlik = $dizi;
				$uzunluk = 0;
			}
			if(($benzerlik <= $uzunluk) || ($uzunluk < 0)) {
				$yakinlik = $dizi;
				$uzunluk = $benzerlik;
			}
		}
		
		$op = "Aranan Kelime: ".$aranan_kelime;
		if($uzunluk == 0) {
			$op .= $yakinlik." için herhangi bir sonuç bulunamadı!";
		} else {
			$op .= "Bunu mu demek istediniz? ".$yakinlik;
		}
		return $op;
	}
	
	/**
	 * Generates a random date between a start date and an end date.
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 */
	public static function makeRandomDateInclusive($startDate, $endDate){
		$days = round((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24));
		$n = rand(0,$days);
		return date("Y-m-d",strtotime("$startDate + $n days"));
	}

	public static function motto() {
		$motto = array(
				'Özlü söz 1',
				'Özlü söz 2',
				'Özlü söz 3',
				'Özlü söz 4',
				'Özlü söz 5',
				'Özlü söz 6',
				'Özlü söz 7',
				'Özlü söz 8',
				'Özlü söz 9',
				'Özlü söz 10',
		);
		return $motto[rand(0, count($motto)-1)];
	}

	private static $lorem = array(
			'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec laoreet aliquet consectetur. In nulla enim, tincidunt ac sagittis adipiscing, dignissim vel diam. Nunc nunc purus, dictum sit amet laoreet id, porttitor ac erat. Nam non quam sapien. Aenean tristique nulla ut justo bibendum adipiscing. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer pulvinar fringilla arcu, sed laoreet purus ullamcorper eget. Morbi luctus vehicula urna, eget dignissim tortor venenatis at. Pellentesque vel dolor ut purus molestie commodo.',
			'Vivamus facilisis egestas rhoncus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec ligula tellus, tincidunt eu tincidunt id, ultrices vitae est. Ut sit amet lorem eu neque tempus rutrum. Phasellus aliquet volutpat leo, quis posuere urna dictum eget. Vestibulum in velit ac velit pulvinar mollis. Curabitur id condimentum purus. Aenean ut libero vel nisl sodales imperdiet.',
			'Fusce tincidunt lobortis libero, vulputate dignissim est tristique fringilla. Curabitur vitae nibh massa, sit amet gravida quam. Praesent id justo ac diam ultricies tincidunt. Duis consectetur cursus augue et fermentum. Nullam eget purus et ante volutpat aliquam. Duis nibh arcu, malesuada ut malesuada at, volutpat eu nisl. Nam placerat ipsum in nisi vulputate lobortis. Morbi aliquet condimentum porta. Duis tincidunt iaculis lacinia. Donec congue lectus ut mi mattis fermentum. Nam mollis erat nec sapien mollis dignissim. Nullam metus est, hendrerit vitae sollicitudin at, molestie eget sem.',
			'Nulla eros enim, vulputate ut rhoncus nec, aliquam vitae ante. Phasellus ac aliquam massa. Aenean congue adipiscing ultrices. Nulla nisl mauris, cursus gravida hendrerit et, dapibus ac tellus. Donec lectus tortor, vestibulum sit amet malesuada luctus, accumsan nec lectus. Nullam a erat eget massa hendrerit venenatis. Proin quis dapibus nisi. Nullam nec mi eget mi congue hendrerit a vel lorem. Suspendisse ligula enim, convallis ultrices vulputate vel, fringilla ac tellus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque imperdiet nulla id odio tempor porttitor. Cras vestibulum orci non dolor pellentesque eu elementum nunc tempus. Ut consequat facilisis dui, non tincidunt augue eleifend nec. Donec ut urna ut nibh vestibulum vulputate nec placerat velit.',
			'Mauris ac enim arcu. In eget dui in est ultricies ornare. Nunc quis dolor felis, nec pretium orci. Donec iaculis magna eget magna aliquam dignissim. Mauris quis diam libero, a vulputate mi. Duis elit neque, consequat condimentum blandit vitae, lobortis id nibh. Mauris lectus nisl, posuere vitae pellentesque ac, pretium ac dui. Maecenas vestibulum volutpat libero, in interdum velit fringilla ullamcorper. Nullam sed ante non felis pharetra dapibus non sed leo. Vestibulum semper sem lorem.',
			'Aenean enim massa, fermentum in condimentum id, iaculis a nisi. Nam euismod nisl at velit gravida euismod. Nulla facilisi. Fusce mauris sem, tincidunt sit amet scelerisque a, sodales tincidunt dui. Etiam sem orci, scelerisque quis sollicitudin vitae, tincidunt eu lectus. Sed iaculis suscipit eros, vel consectetur dui laoreet eu. Suspendisse sed est gravida dui accumsan accumsan a quis turpis. In consequat risus vestibulum est rhoncus id imperdiet velit iaculis. Maecenas eleifend feugiat lorem, et dictum metus venenatis id. Cras velit sapien, aliquet eget sagittis at, luctus nec risus. Curabitur urna turpis, consequat eu varius at, varius vel risus. Vivamus interdum, leo ut congue tempor, metus quam iaculis justo, volutpat placerat lacus erat quis tellus.',
			'Nulla neque augue, feugiat quis consequat ullamcorper, iaculis quis erat. Quisque id augue ante. Pellentesque vehicula nisi in ligula mattis pretium consequat purus vulputate. Nulla ultricies ipsum eget magna imperdiet a suscipit massa aliquam. Integer in libero leo. Fusce sed libero id est auctor aliquam. Fusce quis bibendum libero. Maecenas dictum lacinia augue non iaculis. Suspendisse sed lacus nisi, imperdiet posuere ipsum. Sed porttitor sapien id arcu dignissim et malesuada enim sagittis.',
			'Maecenas eget ante quis justo aliquet consequat vel non nibh. Ut massa nisi, vehicula ac fermentum non, imperdiet in sem. Sed tristique porta rutrum. Etiam at arcu nibh, eget aliquam ante. Vivamus sagittis tempus purus vel adipiscing. Duis sagittis dapibus dui, id venenatis purus feugiat id. Sed id ante lectus.',
			'Donec elementum volutpat lectus, in volutpat nunc molestie nec. Integer pellentesque nunc ut ipsum dignissim a posuere metus egestas. Duis sapien enim, varius ut convallis et, adipiscing non diam. Quisque volutpat, quam non cursus vehicula, est lectus adipiscing tellus, eu gravida magna justo et elit. Nulla in tortor risus. Proin tincidunt dignissim erat ut volutpat. Ut dolor ante, blandit sit amet tincidunt ac, convallis et nunc. Ut faucibus condimentum lacus, sit amet blandit dolor auctor et. Integer ac erat at nisl molestie pulvinar.',
			'Praesent ac viverra justo. Etiam pharetra condimentum massa eget fringilla. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Etiam nisl nisi, laoreet vitae lobortis at, venenatis at nibh. Aliquam tortor dolor, tincidunt id volutpat non, commodo in elit. Morbi bibendum elit feugiat ligula tincidunt ullamcorper. Aliquam euismod elit eu velit pharetra commodo. Praesent eu erat ac dolor commodo bibendum.',
			'Nullam est purus, iaculis a aliquet eu, sagittis non risus. Ut volutpat diam vitae urna cursus auctor. Vivamus dui arcu, mollis vel tincidunt tristique, dictum ut tortor. In a volutpat risus. Nulla sit amet mi augue. Sed porta mi sit amet eros placerat in ultricies lectus molestie. Aenean commodo venenatis lacus id elementum. Nulla augue metus, euismod faucibus blandit lobortis, gravida a neque. Sed ullamcorper rutrum sem, sed lobortis arcu varius at.',
			'Sed eget urna dui, ac venenatis leo. Quisque suscipit hendrerit varius. Vivamus ullamcorper nunc vitae est pulvinar sed pretium magna aliquam. Ut interdum, augue eu accumsan feugiat, arcu odio ultrices turpis, sit amet vulputate dui arcu in neque. Phasellus elementum dolor eu odio iaculis ac posuere dui auctor. Suspendisse luctus semper metus sit amet adipiscing. Duis nec nulla ipsum. Aenean pharetra lectus interdum mauris suscipit convallis eget quis nulla. Fusce egestas imperdiet vestibulum. Sed dui nulla, adipiscing at auctor non, ornare vitae metus.',
			'Etiam suscipit vestibulum sapien nec dignissim. In malesuada consequat tellus, non imperdiet orci varius ac. Donec commodo auctor venenatis. Nam tristique volutpat diam non cursus. Nunc tristique enim id ante porttitor non condimentum libero luctus. Donec porttitor, libero vel mattis imperdiet, dui ante gravida purus, gravida malesuada turpis massa vitae est. Pellentesque feugiat erat vel metus varius vel consectetur leo iaculis. Praesent diam massa, pellentesque at vehicula et, sollicitudin sit amet lectus. Donec nisi purus, volutpat non imperdiet eget, eleifend nec mi. Cras convallis nisi ut arcu auctor faucibus. Vestibulum eu eros condimentum erat fermentum sollicitudin.',
			'Nunc bibendum purus vitae magna scelerisque tincidunt. Fusce iaculis mollis placerat. Aliquam ultrices sagittis iaculis. Ut nec felis eget nisi molestie aliquam. Nunc quis lacus augue, et fringilla diam. Duis diam ipsum, pellentesque nec adipiscing non, accumsan ac dui. Aenean condimentum lectus at enim consequat sit amet placerat arcu sollicitudin. Sed semper mi nec orci convallis aliquet. Sed nec ante metus, lacinia molestie ante. Duis eu est massa. Donec auctor malesuada sem, sit amet placerat lacus pretium a.',
			'Integer faucibus, nisi a faucibus laoreet, lectus justo elementum urna, placerat dignissim dolor tellus non quam. Duis neque elit, luctus euismod scelerisque at, hendrerit at turpis. Vestibulum quis lorem diam, ac dictum nisi. Fusce lobortis iaculis facilisis. Phasellus pharetra ultricies varius. Vestibulum mauris tortor, volutpat et interdum gravida, rutrum et lorem. Sed nec neque erat, vel varius sem. Nunc nec consequat dui. Proin egestas lectus eu purus molestie feugiat. Donec placerat velit ut augue suscipit ut ultricies orci euismod.',
			'Morbi dapibus dapibus ullamcorper. Cras vitae gravida mauris. Integer congue ornare leo eget molestie. Vestibulum malesuada imperdiet vestibulum. Suspendisse orci arcu, fermentum sed dapibus ac, malesuada eget neque. Donec posuere risus eget lectus porttitor non condimentum metus sagittis. Sed id tellus lacus. In euismod congue quam sed facilisis. Nam non diam nibh, non lobortis tellus. Phasellus lectus quam, venenatis quis rutrum a, mattis at ligula.',
			'Nunc libero diam, aliquam a sagittis eget, ullamcorper et diam. Cras vitae sapien nisl, pulvinar aliquam ligula. Maecenas dignissim pretium tellus, non mattis ligula bibendum vel. Integer sagittis blandit metus, quis fermentum tortor dapibus vel. Donec tellus elit, dapibus nec fermentum sed, hendrerit in leo. Duis feugiat mattis quam id laoreet. Nunc ut orci mi, in malesuada metus.',
			'Aenean laoreet bibendum justo scelerisque iaculis. Sed nisl massa, consequat a luctus quis, molestie et massa. Etiam congue ullamcorper nibh non fermentum. Morbi tempus, diam in semper porttitor, turpis justo semper enim, nec egestas orci quam nec enim. Aenean venenatis porta augue sed facilisis. Ut augue risus, mollis et gravida ac, porttitor at massa. Aliquam erat volutpat. Quisque tristique dolor ac tellus adipiscing lobortis. Sed elit risus, tempus ut rutrum eu, elementum euismod metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras in blandit nisl.',
			'Sed ut lorem a nisl hendrerit tristique id auctor odio. Vivamus fermentum nisl a nibh volutpat congue. Integer fermentum accumsan consectetur. Nullam pretium porttitor sem vitae porta. Praesent malesuada feugiat dignissim. Morbi interdum turpis vitae orci condimentum eget tincidunt ligula scelerisque. Vestibulum ligula eros, scelerisque at laoreet ac, ultricies nec lacus. In orci enim, hendrerit et posuere in, dictum quis est. Nulla sed turpis convallis magna laoreet elementum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
			'Integer elementum metus non magna cursus non pellentesque libero commodo. Quisque vulputate suscipit pellentesque. Fusce eget tellus id arcu faucibus dictum. Cras quis gravida diam. Pellentesque euismod viverra neque, a pellentesque elit dictum ut. Proin in diam sit amet nulla elementum placerat ac quis magna. Fusce ultricies rhoncus metus. Ut ultricies, velit sit amet aliquam laoreet, sem justo scelerisque magna, eget placerat lorem libero vel arcu. Vestibulum venenatis tincidunt tortor sed aliquet. Duis tempor quam a nunc tempus dapibus. Cras et ante nibh, vel ornare arcu. Suspendisse euismod lacus a ipsum condimentum non placerat dolor luctus. Nullam sed risus tellus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam vulputate arcu ut ipsum dignissim tempus.',
			'Nullam blandit lorem egestas elit ullamcorper placerat. Phasellus eget velit turpis, at tincidunt purus. Etiam diam tellus, dignissim eget laoreet sit amet, lobortis in orci. Cras ac dapibus lacus. Mauris interdum, dolor sed commodo porta, mi lectus semper lacus, et gravida erat libero quis ligula. Maecenas vehicula, dolor at volutpat tincidunt, diam eros malesuada justo, dapibus eleifend nisl felis sed libero. In dignissim cursus elit, vel fermentum tortor sagittis commodo. Vestibulum non est purus. Maecenas eget quam sed augue porta adipiscing id ac diam. Nulla facilisi. Ut dignissim blandit elit. Maecenas suscipit, justo tincidunt pretium imperdiet, justo ligula auctor enim, vel vehicula lacus elit eget purus. Vestibulum vel nibh odio. In a diam magna. Morbi sit amet ipsum eu eros adipiscing laoreet in ut nisi.',
			'Nulla facilisi. Curabitur auctor, lorem sed vehicula luctus, urna mi consequat ante, non egestas leo justo nec nulla. In elit nibh, dictum a suscipit ultrices, dapibus ut urna. Nulla a sagittis nisl. Proin id massa odio. Integer euismod diam eget neque congue id dapibus elit sollicitudin. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec quis orci dolor. Aenean nec nibh at dui imperdiet tincidunt. Nulla facilisi.',
			'Praesent nec leo a lorem tincidunt convallis. Integer justo nisi, accumsan sit amet dignissim eu, pellentesque non tortor. Donec at diam felis. Etiam elementum luctus consequat. In hac habitasse platea dictumst. Sed gravida posuere semper. Integer felis metus, consequat eu accumsan at, eleifend et magna. In sagittis pulvinar dictum. In vel ante orci. Vestibulum quam enim, semper at bibendum vitae, molestie vel diam. Nullam pharetra volutpat convallis. Cras quis dui neque. Sed posuere pulvinar augue, at convallis nibh porta sit amet. Praesent tristique ante et urna faucibus sit amet venenatis leo lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi facilisis magna sit amet odio aliquet at accumsan magna aliquet.',
			'Ut bibendum aliquam magna sed auctor. Donec placerat lorem eget justo pulvinar id consectetur magna cursus. Maecenas sed placerat eros. Ut massa nisi, commodo vitae elementum vel, ornare vel lacus. Nam mattis volutpat elit, at imperdiet felis facilisis in. Suspendisse malesuada dolor id tortor lacinia vulputate. Duis mattis nisl vitae lorem consequat euismod. Nulla facilisi. Aliquam facilisis accumsan libero. Proin nisi diam, tempus ut pharetra nec, luctus id urna. Maecenas venenatis hendrerit lorem in accumsan.',
			'Suspendisse hendrerit, diam a malesuada aliquet, erat mi varius dolor, a consectetur nisi orci feugiat lacus. In fermentum sem eget massa ornare imperdiet. Duis eget metus in nisl pulvinar vulputate. Mauris non ornare arcu. Quisque vestibulum lectus a ante pharetra consequat. Fusce facilisis mollis tempus. Nullam eu sollicitudin lectus.',
			'Curabitur ut elit sem, et gravida dolor. Suspendisse vitae odio sodales lacus consequat sagittis vel in ante. Duis diam nulla, lobortis nec ullamcorper quis, ornare eu libero. Sed dignissim porta purus. Mauris et blandit nibh. Aliquam a nisi elit. Morbi mollis, leo a tempor fermentum, dolor tortor scelerisque est, eu porttitor sem magna at lacus. Vestibulum vel purus quis urna vehicula rutrum at in leo. Ut hendrerit erat sit amet enim venenatis dapibus.',
			'Duis eleifend semper mauris, eu consectetur felis faucibus et. Cras luctus arcu et elit interdum eu consectetur risus tincidunt. Nam nec enim diam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Maecenas egestas leo ornare tortor ultrices nec ultrices ipsum consequat. Nunc volutpat adipiscing adipiscing. Integer rutrum, tortor vestibulum feugiat vulputate, lectus velit vulputate urna, tempus vestibulum ante nisi vel eros. Morbi pellentesque arcu elit. Nunc et est sed quam hendrerit viverra. Proin id ligula et ante pulvinar luctus nec eu turpis. Praesent eget risus urna, nec varius nibh. Cras ac nisi eu velit pellentesque scelerisque sed ac nisi. Nulla facilisis tempor odio, et ullamcorper nunc imperdiet sit amet. Aliquam sagittis congue ante, rhoncus sagittis nisl hendrerit scelerisque. Aenean ultricies condimentum massa varius euismod. Ut ut turpis a metus consectetur aliquam eu ut erat.',
			'In congue lobortis sapien sed ullamcorper. Etiam mattis lorem nec nibh rutrum at ornare nibh fringilla. Etiam et sagittis ipsum. Vivamus mollis aliquet ipsum, id vehicula quam tincidunt eget. Donec est mi, congue ut ultrices eget, pulvinar sit amet turpis. Etiam bibendum odio et nisi auctor hendrerit. Nunc in nisi dolor. Nam bibendum venenatis diam, quis fermentum sapien mollis non. Vestibulum vel ante dui. Duis iaculis rhoncus placerat. Ut quis mauris in augue volutpat viverra quis id velit. Duis feugiat massa sapien. Nam diam ligula, viverra eu auctor sed, lacinia vel augue. Mauris quis nulla urna. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
			'In hac habitasse platea dictumst. Quisque sit amet lectus nulla, feugiat feugiat nisi. Vivamus in nunc urna. Vivamus non lorem leo. Phasellus eget felis eget magna egestas rutrum ac sit amet eros. Integer quis lorem nisi, sit amet blandit urna. Donec bibendum metus nec enim ornare sed suscipit nulla porta. Proin ornare pharetra vehicula. Duis adipiscing tellus vitae massa rutrum ut sodales nunc blandit. Maecenas sagittis bibendum nisi, ac eleifend eros posuere vitae.',
			'Morbi nec viverra neque. Donec purus lorem, feugiat in mattis vitae, porta ac nisi. Quisque a eros vitae velit eleifend pharetra. Morbi sit amet suscipit libero. Curabitur tempus, lorem ac mattis fermentum, arcu turpis ultrices tellus, ut semper tellus orci in magna. Praesent pulvinar tempus viverra. Quisque eu ultrices nunc. Quisque felis eros, cursus ac tristique eu, adipiscing quis eros. Proin non neque ligula, sed hendrerit neque. Pellentesque volutpat gravida vehicula. Aliquam erat volutpat. Cras id fringilla diam. Phasellus dui augue, lacinia pretium elementum sed, lobortis id sem. Mauris aliquet, sapien id auctor congue, eros tellus ultrices ipsum, ac luctus velit nisl eget mi.',
			'Mauris lacus nisl, aliquet sit amet tempor sodales, iaculis pharetra dolor. Nulla nec scelerisque mi. Aliquam pellentesque facilisis mauris at mattis. Nulla mollis placerat pulvinar. Donec et justo at purus ullamcorper tincidunt quis vitae enim. In dictum leo sit amet nibh rutrum porttitor. Pellentesque consequat elementum suscipit. Pellentesque ultricies turpis non quam porttitor rutrum. Integer sit amet purus nisl.',
			'Mauris turpis sem, imperdiet id dignissim nec, venenatis non orci. Vivamus tincidunt, leo quis fringilla faucibus, nisi sapien lacinia justo, non faucibus dui erat ac urna. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris iaculis vulputate commodo. Aliquam tempor metus quis dolor viverra porta. Integer ullamcorper pulvinar velit, viverra tristique nunc luctus non. Nulla facilisis metus ac elit facilisis quis fermentum nulla convallis. Sed a augue non ante iaculis hendrerit eu id sapien. Sed nunc elit, posuere et consectetur eu, gravida ut erat. Sed imperdiet mattis libero, non lacinia metus rutrum et. Praesent eu dui eros, adipiscing malesuada metus. Pellentesque vel velit mauris.',
			'In fringilla, neque vitae aliquam bibendum, nibh quam tincidunt diam, sed molestie sem orci sed elit. Morbi ac purus non nisi ultrices venenatis. In consectetur, nisi in faucibus tincidunt, quam tellus congue sapien, vel mattis erat mi a urna. Quisque fringilla rutrum sollicitudin. Vivamus sapien nisi, ornare eu facilisis lacinia, vehicula vel purus. Pellentesque quis dolor id est commodo elementum tempus non nulla. Donec vel urna lacus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Maecenas fermentum ante eu velit pharetra viverra. Nunc vitae nisl ut erat pharetra lobortis. Duis auctor erat ut ligula malesuada placerat tincidunt eros sollicitudin.',
			'Vestibulum est ligula, placerat et dapibus eu, semper non felis. Nam molestie pellentesque ante, in dictum ligula mattis ut. Aliquam tempor ultrices risus sit amet egestas. Cras ac odio vel massa facilisis vestibulum. Fusce mollis odio at metus volutpat a commodo diam sodales. Sed tempus nibh fringilla nibh fringilla aliquet bibendum mi gravida. Maecenas euismod orci ac mi mattis viverra. Sed ac velit libero, ut vestibulum velit. Quisque pretium convallis condimentum. Ut convallis volutpat dui quis rutrum. Quisque est dolor, rhoncus at venenatis at, mollis id diam. Nunc a mauris risus. Ut vestibulum suscipit tellus, id scelerisque ante pulvinar nec. Pellentesque sed nulla arcu, sit amet elementum metus. Sed eleifend rutrum semper. Sed sit amet mollis nisi.',
			'Curabitur non magna in ante pharetra porttitor suscipit non est. Vestibulum sed eros in leo consectetur cursus. Donec elementum augue in augue feugiat mollis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam tortor est, molestie ut blandit a, molestie ac eros. Praesent faucibus tempus dui, sit amet facilisis augue laoreet a. In consequat nisi et velit adipiscing vehicula ultrices elit aliquam. Vivamus dapibus lacus ac metus ornare sit amet imperdiet ligula facilisis. Fusce vestibulum est nec dolor egestas quis fermentum odio scelerisque. Pellentesque ultrices purus in turpis ultricies aliquet. Mauris diam magna, elementum non dapibus eget, tempor sit amet sem. Cras tempor, ligula sed accumsan mollis, nisi augue ullamcorper mi, nec luctus nibh enim sed ipsum. Duis malesuada iaculis ultricies. Donec vitae felis vel sapien ornare interdum nec ut augue.',
			'Integer nec sodales sapien. Donec dictum aliquet elit vel mollis. Nunc eget accumsan elit. Suspendisse in dui vel leo dictum eleifend nec ut purus. Donec ullamcorper auctor erat. Aenean est elit, accumsan sit amet tincidunt mattis, suscipit in arcu. Aliquam erat volutpat. Vestibulum mollis imperdiet felis, vitae placerat diam rhoncus sit amet. Nunc vel sapien a mi vulputate congue. Ut interdum cursus ante nec scelerisque. Nullam ut pharetra ante. Aenean luctus gravida rhoncus. Aliquam vitae lectus lorem, sit amet luctus odio.',
			'Nunc nec pharetra lorem. Nullam gravida sem quis sem facilisis luctus. Aenean orci ligula, porttitor ac ornare sed, porttitor pretium tellus. Donec sollicitudin dapibus ipsum non varius. Phasellus ante arcu, elementum quis tempor id, hendrerit a mauris. Proin elementum, ligula id consectetur viverra, turpis libero ultricies metus, euismod iaculis quam felis ac ligula. In hac habitasse platea dictumst. Donec sed nibh eu lorem luctus suscipit. Morbi sit amet ante at nisl dignissim mattis. In in leo tellus, sed feugiat risus. Integer vehicula risus sapien. Donec auctor aliquam molestie. Maecenas mattis leo erat. Proin nec aliquet massa.',
			'In sed arcu magna. In lacinia mauris id massa sollicitudin in tincidunt neque sollicitudin. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Phasellus et neque ac augue dictum vulputate. Sed commodo sodales est eget pellentesque. In iaculis pharetra massa eu posuere. Cras commodo erat quis leo pretium ut tincidunt nibh posuere. Praesent ut dolor quis metus eleifend euismod. Nam consequat felis eu augue posuere imperdiet. Morbi ut orci ac neque fermentum hendrerit ac scelerisque ligula. Proin volutpat, velit at vehicula feugiat, dui diam rutrum arcu, vitae ultricies dolor enim in tortor. Sed aliquam sem sed felis congue vel consequat leo sodales. Pellentesque tristique lorem vitae erat consequat vulputate.',
			'Cras dui quam, pretium sit amet lacinia sed, porttitor sit amet neque. Aliquam tincidunt, purus quis sagittis tempus, libero purus blandit tellus, ut congue odio lorem et orci. Ut ornare fringilla neque luctus ultrices. Morbi blandit erat sed diam fermentum volutpat. Aliquam hendrerit sodales arcu condimentum aliquet. Aenean dolor leo, congue eget eleifend vitae, eleifend et felis. Donec semper porttitor lacinia. In commodo, dui eget facilisis porta, sapien dolor imperdiet lorem, id aliquam turpis nunc eu felis. Sed vitae tortor at purus congue posuere. Donec ligula dolor, scelerisque nec semper ac, porttitor et libero. Vivamus vel diam ut enim ultrices egestas.',
			'Mauris eleifend est at elit auctor ornare. Proin sollicitudin dolor metus, ut sollicitudin massa. Fusce erat augue, congue ut feugiat nec, euismod ut ligula. Morbi sed neque orci, id laoreet odio. Morbi elit metus, accumsan vel condimentum non, porta a mi. Praesent consequat tellus sed elit dignissim condimentum. Praesent rhoncus semper arcu, ut facilisis leo imperdiet non. Vestibulum ligula arcu, tristique non ultricies non, accumsan ut est. Maecenas egestas pulvinar egestas. Nulla pretium dui non lorem vestibulum vitae egestas tortor mollis. Nulla augue lectus, cursus at placerat eleifend, tempus quis purus. Suspendisse porttitor dui ac magna bibendum a consectetur nulla bibendum. Sed sit amet felis quis libero tincidunt consequat. Nullam facilisis commodo tortor, et porttitor neque aliquet a. Aliquam interdum blandit massa.',
			'Fusce neque arcu, accumsan porttitor bibendum et, venenatis ac purus. Maecenas et nulla in ipsum ultrices feugiat a a nisi. Aenean dignissim justo venenatis velit eleifend eget sodales nulla vestibulum. Vestibulum molestie sapien nec neque pretium quis rutrum mi fringilla. Maecenas ipsum tortor, molestie vitae vestibulum nec, ultricies luctus velit. Fusce vestibulum ipsum at turpis auctor at vehicula lectus adipiscing. Duis non nibh in felis dignissim viverra in sit amet tortor. Aenean ipsum nibh, malesuada non dictum eget, ornare id libero. Integer eget mauris nisi, et aliquam est.',
			'Phasellus euismod, justo scelerisque vehicula euismod, urna turpis interdum enim, sed consectetur orci augue ac elit. Proin ultricies sodales sapien, in condimentum urna laoreet aliquam. Suspendisse enim nisi, placerat non dignissim in, luctus quis tellus. Nullam eget ligula ut ipsum gravida auctor. Nunc porttitor pharetra urna nec molestie. Cras et facilisis risus. Nullam dignissim facilisis eros, et ultricies metus imperdiet ac. Etiam nisl metus, fermentum quis ullamcorper vel, commodo eget dolor. Donec sodales ornare imperdiet. Proin ac semper justo. Ut pulvinar mattis eros, a venenatis nisl dapibus et. Pellentesque aliquet, neque non ullamcorper ullamcorper, lectus turpis consectetur enim, a dignissim elit turpis quis eros. Aliquam erat volutpat. Etiam ac gravida mi. Fusce in adipiscing arcu. Integer at turpis magna, sed pretium nibh.',
			'Duis placerat facilisis porta. In hac habitasse platea dictumst. Proin tempor lacus quis tellus pulvinar porttitor. Aenean lobortis sodales facilisis. Nunc vehicula condimentum varius. Etiam neque neque, sagittis a vehicula eu, varius in elit. Maecenas non est vitae leo dapibus ultrices. Morbi et urna nisl. Vivamus purus nisl, faucibus eget sollicitudin quis, congue in metus. Maecenas et lacinia libero.',
			'Nam tincidunt adipiscing nulla sed lobortis. Sed ante ante, malesuada ut lacinia eu, rutrum nec erat. Suspendisse facilisis mi ut nisi sollicitudin at ultrices nisl rutrum. Donec consequat nisl id mauris lobortis tempor. Maecenas pulvinar commodo faucibus. Curabitur vitae libero vitae tortor imperdiet semper. Ut auctor fermentum pharetra. Sed condimentum erat et erat accumsan ut pellentesque nulla lacinia. Ut euismod malesuada arcu sit amet vehicula. Praesent mattis ligula ut turpis sodales dignissim. Morbi facilisis tempus nunc, sit amet molestie odio malesuada eget. Aliquam convallis fermentum est. Fusce blandit vehicula nunc ut blandit. Vestibulum dapibus, nisl viverra posuere volutpat, lacus est tincidunt mi, a iaculis purus ipsum in nisl. Praesent faucibus purus at nisi pretium at elementum erat euismod. Aenean volutpat tristique urna, in vestibulum diam condimentum non.',
			'Aliquam non nibh orci. Ut facilisis ullamcorper aliquam. Duis pellentesque dui id sapien volutpat iaculis. Quisque scelerisque volutpat elit, ut ultricies odio suscipit quis. Fusce dapibus tempor elit ut congue. Vivamus posuere tempor mauris in rhoncus. Sed lacinia vehicula arcu eu consectetur. Pellentesque eu turpis eget justo facilisis dignissim. Fusce ultricies aliquet scelerisque. Suspendisse potenti.',
			'Sed magna magna, commodo ac sollicitudin ut, venenatis sit amet magna. Quisque semper malesuada lacus in tempor. Pellentesque blandit, metus ac pulvinar lacinia, nisl diam imperdiet elit, eu convallis erat eros ac nisl. Duis eu dui vitae felis posuere bibendum. Ut libero velit, dapibus sit amet tempus sed, egestas vitae enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nam ac rutrum leo. Mauris nec quam nisi. Suspendisse ultricies vulputate dapibus. Sed eu eros a dolor ultrices sollicitudin pulvinar ac enim. Mauris ullamcorper euismod neque, id auctor tellus adipiscing vel. Nam non placerat magna. Ut libero leo, ultricies non convallis ullamcorper, faucibus quis orci. Donec tristique nulla eget tortor dignissim rhoncus. Nunc tempus est sit amet lorem mollis at blandit arcu fermentum. Nunc ultrices tortor aliquet ipsum aliquam aliquet.',
			'Ut vitae massa quis elit placerat adipiscing. Quisque vulputate adipiscing metus id lacinia. Phasellus tempor orci et odio tempor dictum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sodales feugiat fermentum. Donec semper vestibulum eros eget condimentum. Morbi mollis ultrices facilisis.',
			'Aliquam feugiat sollicitudin metus, id luctus tortor consectetur vel. In aliquet porttitor lacinia. Maecenas sit amet adipiscing ante. Sed sagittis tincidunt metus et hendrerit. Donec condimentum nunc sapien, et placerat tellus. Curabitur odio metus, accumsan malesuada posuere sed, sodales sit amet sapien. Nulla facilisi. Ut porttitor, lectus quis tempor cursus, dui sapien sagittis arcu, tristique euismod dui turpis ut diam.',
			'Aliquam mattis pretium ipsum, eget tincidunt mauris iaculis in. Suspendisse lacinia tristique tortor, eu mollis leo venenatis eu. Vivamus ipsum elit, ultrices id pharetra a, porta at velit. Quisque euismod ligula dapibus diam convallis porttitor. Nulla a velit felis. Curabitur feugiat magna ornare nulla tempus bibendum. Sed accumsan tellus scelerisque mauris ultricies a rhoncus massa luctus. Integer ornare pharetra convallis. Nunc mattis aliquam urna. Suspendisse bibendum, tellus sit amet suscipit dapibus, ligula metus posuere quam, ut mollis dui mi sed justo. In volutpat risus sed purus varius pellentesque interdum erat tempor. Pellentesque id rhoncus neque. Praesent tempus sollicitudin urna, non pretium turpis posuere congue. Etiam cursus tortor id nunc scelerisque at dignissim nisl laoreet.',
			'Ut gravida pellentesque dictum. Sed a nunc at dui cursus consectetur. Vestibulum faucibus accumsan felis, in pharetra nisi ultricies vel. Nulla fermentum sapien eu velit hendrerit nec tincidunt dui gravida. Duis convallis luctus massa id venenatis. Ut nec mauris in quam consequat lacinia nec non enim. Fusce tempor congue tellus, in scelerisque quam blandit sed. Praesent vel viverra ligula. Aliquam vel sollicitudin lorem. Nunc elementum hendrerit neque, ut mattis felis blandit sed. Cras scelerisque, elit vitae bibendum ultricies, magna mi tincidunt quam, a dignissim lectus orci quis justo. Vivamus hendrerit mauris vel lorem semper vitae hendrerit lacus euismod. Maecenas vitae leo ut felis ultricies pharetra. Duis fringilla risus sed ligula congue non malesuada felis fermentum. Nulla facilisi. Suspendisse a felis at tortor suscipit blandit at a ipsum.',
			'Nunc tellus purus, feugiat nec tempus vel, dictum vitae lorem. Morbi viverra, est non ornare tristique, quam lectus pretium turpis, vel commodo felis diam eget leo. Proin non ante nec mauris faucibus aliquam at rhoncus velit. Sed sit amet enim metus, nec vehicula felis. Nam elit quam, commodo consectetur blandit non, malesuada quis neque. Sed vel augue lorem. Nam ut auctor orci. Sed interdum, ante ac euismod ullamcorper, nisl turpis volutpat leo, eget vulputate felis libero eget sapien. Suspendisse imperdiet ipsum in augue rutrum tristique. Vestibulum dictum eleifend libero, quis tempor orci elementum eu. Etiam sit amet tellus sit amet leo dapibus vehicula at id magna. Cras feugiat tempus vestibulum. In nec nunc elit, sed imperdiet justo.',
			'Aenean id massa non diam elementum vestibulum et id sem. Maecenas neque leo, pellentesque sed viverra et, viverra vel sem. Integer viverra nulla quis magna ultricies facilisis. Ut arcu leo, cursus imperdiet mollis ut, dapibus et dui. Mauris in odio lectus. Sed sollicitudin ligula eu mi vestibulum sit amet commodo turpis iaculis. Morbi vitae nibh nec risus accumsan tempor quis non tellus. Integer pretium libero a ante lobortis eu sodales lectus egestas. Pellentesque aliquam nunc quis eros vestibulum eleifend. Nullam ultrices commodo tellus ut blandit. Phasellus sit amet velit nunc, et cursus augue. Proin vel dui non magna interdum posuere. Ut ut fringilla metus.',
			'Vestibulum turpis justo, porta sit amet bibendum at, venenatis quis nisi. Fusce posuere enim sed nibh eleifend consectetur. Nunc varius ligula eu lacus sollicitudin ultrices. Aenean in egestas magna. Donec sit amet purus dui. Mauris sapien elit, porta in vehicula et, congue id lorem. In hac habitasse platea dictumst. Praesent lacinia quam nec erat pharetra lobortis. Vestibulum euismod commodo posuere. Duis nulla purus, porta eget accumsan eu, consectetur sit amet turpis. In non risus ligula. Pellentesque porttitor libero et sapien mollis cursus. Integer vestibulum mi a lectus feugiat in semper dui mollis. Curabitur lobortis neque lorem, at venenatis erat. Etiam quis felis sodales nisi molestie tristique.',
			'In ac arcu sapien, eu congue sapien. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eget erat libero. Sed convallis, tortor interdum tincidunt auctor, enim leo sagittis est, a cursus libero ipsum a libero. Phasellus at lacus vitae orci tincidunt congue. Mauris imperdiet, dolor in dictum fermentum, enim massa tincidunt justo, nec sollicitudin nisl neque eu nibh. Vestibulum vitae imperdiet orci. Morbi lacinia sodales nisl, quis interdum nunc eleifend sit amet. Nullam nunc leo, consequat sed tincidunt a, eleifend id massa. Phasellus eget ultricies quam. Proin ut faucibus sem.',
			'Donec venenatis blandit quam, non consequat urna auctor id. Cras varius mollis tempor. Sed et urna lorem. Vestibulum auctor purus id sapien faucibus porttitor. Suspendisse lacinia magna at dui mollis sed tristique turpis venenatis. Donec laoreet molestie felis vel congue. Nullam et arcu in felis euismod sollicitudin. Aliquam tempor leo a elit facilisis a lacinia dolor auctor.',
			'Sed sit amet nisl ante, non consectetur nunc. Nulla et ante dui, vitae vehicula nunc. Proin pulvinar imperdiet ligula fringilla auctor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed risus nulla, tristique non hendrerit at, rhoncus ut turpis. Nullam feugiat rhoncus tortor placerat dapibus. Morbi libero diam, malesuada eu auctor at, placerat vel tellus. Mauris rhoncus, metus quis condimentum fermentum, erat ipsum ullamcorper justo, a semper metus ante eget diam. In tincidunt eros a leo convallis ac luctus eros adipiscing. Ut suscipit, augue quis ultricies tincidunt, nunc ipsum eleifend felis, vitae blandit augue tortor sit amet odio. Duis porttitor porta tempor. Suspendisse molestie rutrum elit, ac hendrerit dolor auctor at. Praesent accumsan justo turpis. Fusce posuere laoreet diam vitae molestie. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
			'Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam erat volutpat. Proin eget ante vel est tincidunt ultricies. Aenean cursus sem non velit convallis laoreet ultricies diam malesuada. Fusce molestie enim et lacus auctor venenatis. In blandit molestie tempor. Sed pretium augue at massa pharetra quis sodales sem fringilla. Sed cursus gravida sodales. In hac habitasse platea dictumst. Aenean rhoncus ornare tempor. Morbi consectetur convallis porta.',
			'Nullam egestas congue justo ac pellentesque. Praesent eu mattis tellus. Aliquam dolor magna, aliquet vel porta quis, laoreet eu erat. In risus libero, pellentesque eget feugiat non, mollis ut lorem. Mauris rhoncus cursus enim a placerat. Aliquam malesuada placerat diam, ac hendrerit mi condimentum at. Cras lacinia adipiscing tincidunt. Aliquam tellus nibh, tempus a hendrerit sed, iaculis eget ante. Donec mattis, quam in consequat feugiat, elit ante sagittis urna, eu bibendum lacus lacus sit amet lorem. Morbi cursus interdum felis, eu imperdiet eros condimentum et. Aenean vel consequat lorem. Etiam magna nisi, cursus et vestibulum vitae, egestas non ligula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ornare imperdiet ipsum, et pretium massa bibendum id.',
			'In eu diam massa, ac adipiscing urna. Integer nunc augue, vehicula non mattis et, elementum eu diam. Morbi libero turpis, porttitor feugiat blandit eu, tincidunt sit amet risus. Duis imperdiet nulla in magna dapibus convallis. Vivamus bibendum augue at ipsum suscipit tempus. Phasellus mi sapien, lobortis sed feugiat vel, ullamcorper sit amet erat. Phasellus pellentesque, lectus a auctor interdum, diam metus fermentum turpis, ut placerat libero nisl placerat justo. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque quis ante sit amet dui cursus dictum. Aenean viverra adipiscing commodo. Vestibulum vel risus vel tortor hendrerit imperdiet. Donec tincidunt ante vel turpis feugiat hendrerit. Maecenas a enim turpis, vitae viverra enim. Sed faucibus libero at ipsum tempus congue.',
			'Suspendisse urna lorem, sagittis id tincidunt nec, venenatis sit amet erat. Donec convallis dui non risus elementum bibendum. Donec a placerat neque. Quisque rutrum dignissim nibh placerat varius. Proin ultricies scelerisque tellus, ut laoreet justo tempor pretium. Vivamus laoreet velit nec lectus blandit a varius nibh imperdiet. Mauris pretium feugiat pharetra. Donec eu dui lorem.',
			'Suspendisse porttitor, libero et scelerisque mattis, mi elit cursus arcu, id laoreet enim turpis eget turpis. Vestibulum sit amet nunc dolor, at egestas eros. Aliquam posuere convallis sodales. Donec mollis sem nisl. Aenean tortor elit, malesuada nec cursus eu, placerat in libero. Mauris iaculis porta purus eget fringilla. Nulla volutpat est et enim fermentum pharetra. Vivamus eros sapien, ullamcorper vel consectetur at, fringilla at est.',
			'Vestibulum a viverra lorem. Praesent dictum nibh quis arcu blandit mattis. Donec ac massa non lacus vestibulum porta eu vitae tortor. Sed ultricies auctor nunc, at vehicula ligula tincidunt in. Aenean eu nisi mauris. Suspendisse magna justo, venenatis eu convallis id, dignissim a libero. Sed hendrerit, tortor ac rutrum laoreet, tortor eros pellentesque mi, id imperdiet sapien justo vel leo. Pellentesque iaculis gravida metus, nec dapibus leo volutpat ut. Pellentesque ornare lacinia velit, ut ullamcorper nisl porttitor fringilla. Nullam tincidunt massa ut elit fermentum ut mattis neque dapibus. Phasellus imperdiet odio ac nibh interdum elementum. Nullam eu mauris est. Vivamus placerat rutrum est, id rhoncus urna eleifend at. Nam nisl libero, vestibulum et tincidunt posuere, tempor elementum mauris.',
			'Ut varius lacinia magna, ut convallis eros sollicitudin nec. Morbi nec massa id massa adipiscing scelerisque. Aliquam ac libero auctor magna tempus mollis eu sed turpis. Nullam cursus odio ut nisl dapibus laoreet interdum nisi venenatis. Sed hendrerit diam nec libero cursus pharetra. Etiam scelerisque ante massa. Ut ultricies hendrerit quam, at consectetur sem ultrices ac.',
			'Nulla non augue nisl, at accumsan libero. Nullam elementum laoreet mattis. Sed facilisis mauris non odio interdum vel eleifend enim fermentum. Ut at venenatis lorem. Donec vestibulum luctus mollis. Sed ornare massa ullamcorper metus feugiat bibendum. Nam placerat molestie nulla, et condimentum felis lobortis quis. Suspendisse sit amet metus neque, non dignissim odio.',
			'Morbi sed risus risus, sed malesuada felis. Sed fringilla imperdiet arcu, nec varius felis rutrum in. Nam et elit eros. Donec a elit sagittis nisi gravida commodo. Sed justo mauris, ullamcorper vitae scelerisque vel, cursus a eros. Vestibulum lorem diam, posuere vitae dapibus dignissim, porta non sapien. Curabitur semper aliquet sem, et malesuada erat fringilla congue. Vestibulum accumsan neque non urna dapibus id posuere metus porta. Sed leo arcu, consectetur eu cursus ac, facilisis non nisi.',
			'Maecenas tristique auctor lorem. Etiam placerat purus eget magna ultricies at tincidunt turpis consequat. Nulla sodales pellentesque eleifend. Integer elementum sollicitudin felis in laoreet. Vivamus et nisl a metus mollis sodales et vitae odio. Vivamus ac quam nisi. Praesent aliquet dignissim elit nec posuere. Aenean congue, quam vel semper vestibulum, eros massa egestas nibh, quis interdum lectus ipsum nec nisl.',
			'Morbi vitae leo tellus. Maecenas urna elit, euismod eget interdum quis, condimentum nec eros. Mauris euismod quam eu sapien scelerisque feugiat. Suspendisse feugiat odio sit amet sapien rhoncus vehicula. Vestibulum at dui nec tellus lacinia iaculis. Aenean ut urna turpis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Quisque gravida, erat nec fringilla lobortis, odio erat consectetur felis, ut commodo est lectus sit amet metus.',
			'Nunc pharetra, leo sed aliquet dapibus, purus mauris dictum odio, in pretium turpis lorem et elit. Nunc quis purus sapien, id venenatis diam. Maecenas vulputate ipsum vitae erat viverra vestibulum. Vestibulum sed orci tellus, a blandit erat. Phasellus porta ligula quis dui tincidunt sodales. Aenean luctus elementum nibh ac ullamcorper. Nulla eget massa turpis, ac mollis felis. In luctus venenatis nibh placerat ornare. Vestibulum venenatis rutrum arcu, placerat auctor neque faucibus id. Donec porttitor sem non sem sodales semper sagittis neque bibendum. Proin nec elit ac lectus consectetur pretium. Integer porta congue nisl, pretium rutrum nisi pulvinar et. Phasellus id sem eu quam dictum faucibus. Sed quis cursus eros. Donec eget feugiat odio. Donec lobortis odio sed sapien tempus et tempor nibh pellentesque.',
			'Nunc mattis, sem eu elementum adipiscing, nunc turpis gravida justo, id fermentum quam magna sed lorem. Vestibulum et placerat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed gravida, lectus id molestie pellentesque, dui dui bibendum odio, quis rutrum risus ante vitae velit. Duis tempus ornare justo, non vulputate libero molestie sit amet. Curabitur dapibus imperdiet fermentum. Nullam justo elit, cursus a euismod eu, consequat sed nunc. Cras metus quam, fringilla nec laoreet vitae, scelerisque id eros. Vivamus sed diam urna. Donec in rutrum velit. Donec id urna ac mauris eleifend scelerisque quis ut massa. Sed lacinia, nisl a dapibus pharetra, mauris odio aliquet justo, vel porta nibh velit a purus. Duis congue feugiat aliquam.',
			'Morbi tristique ornare libero, ac tempus risus euismod id. Nulla vel odio id dui feugiat semper in non dolor. Vestibulum egestas odio at tellus eleifend eget euismod lacus consectetur. Suspendisse nec mollis eros. Quisque id facilisis felis. Nam vel vehicula quam. Suspendisse et justo non dolor auctor congue at id diam. Nulla ullamcorper urna quis augue adipiscing pulvinar. Quisque eu posuere libero. Mauris vel lectus sed turpis commodo iaculis. In facilisis aliquet ligula, eu elementum lorem varius sed. Sed faucibus, metus bibendum accumsan scelerisque, libero ipsum commodo ante, nec rhoncus tortor eros quis mauris. Nullam placerat adipiscing elit, nec iaculis dui dignissim sagittis. Ut commodo lorem vel dui facilisis a vestibulum leo hendrerit.',
			'Curabitur rutrum mi justo, non dictum orci. Phasellus gravida tempus commodo. Nulla vel massa at erat blandit elementum vel sed augue. Integer lorem risus, facilisis quis feugiat nec, mollis sed justo. Nulla hendrerit sagittis placerat. Sed enim ligula, placerat vitae eleifend quis, sollicitudin vitae turpis. Aenean sagittis augue non felis ullamcorper ultrices. Donec in libero sit amet risus sollicitudin luctus ut ut lectus. Aenean porta purus sed risus tempor vehicula. Aliquam erat volutpat. Morbi et orci quis neque viverra luctus euismod vel odio. Pellentesque et elit nulla, sed tincidunt arcu. Vestibulum molestie, neque eu laoreet sodales, dolor diam facilisis diam, quis tristique elit risus sed massa. Ut pharetra elementum enim, non blandit turpis varius at.',
			'Fusce cursus semper sollicitudin. Nam volutpat erat in leo gravida vel elementum sapien cursus. Nulla urna lacus, vestibulum eu viverra at, congue nec dolor. Cras cursus molestie dui, vel pharetra erat scelerisque a. Donec imperdiet ligula tellus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc vulputate scelerisque orci eu pulvinar. Nulla a porttitor orci. Integer ornare adipiscing nisl, at condimentum velit commodo a. Aliquam leo nibh, vehicula fermentum auctor scelerisque, hendrerit eget urna.',
			'Sed non dolor libero. Vestibulum porta libero lectus. Aliquam erat volutpat. Duis pretium purus auctor risus iaculis hendrerit. Sed dictum interdum nisl vitae fringilla. Quisque eu diam in quam malesuada blandit. Morbi et est turpis, ac malesuada nulla. In rutrum ultrices quam, ac imperdiet lorem egestas eget.',
			'Nulla at neque in dui eleifend ultrices in vitae neque. Integer risus justo, vehicula non tempus eu, eleifend quis dolor. Praesent dictum, eros et feugiat fringilla, erat leo ultrices risus, sit amet pellentesque enim diam non enim. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris mollis porttitor elit vel dignissim. In hendrerit auctor purus ac blandit.',
			'Morbi malesuada ipsum eu purus porttitor non imperdiet arcu pulvinar. Vestibulum quis fringilla tellus. In eget libero justo. Cras vel ante lectus, eu sollicitudin dui. Pellentesque sit amet odio augue, sed accumsan massa. Fusce vitae interdum est. Aliquam ut mauris at eros sodales pulvinar eget in nisl. Nulla venenatis, velit sed semper posuere, nisi justo varius leo, varius pretium lorem sem in ipsum. Etiam sem libero, semper in vestibulum sagittis, consequat sed odio. Nunc ut dolor sed ante molestie auctor. Nam vehicula tristique pulvinar. Quisque venenatis nibh dictum neque gravida bibendum tristique ipsum varius. Fusce aliquam lectus vitae eros vestibulum tincidunt. Aliquam non enim et magna placerat laoreet id a lacus. Vivamus sed orci mi. Phasellus porttitor ligula eget mi blandit in venenatis nunc mollis.',
			'Suspendisse varius rhoncus cursus. Sed vitae sem eget magna porta placerat vitae eu nisl. Nam non malesuada ligula. Sed non augue eu nisl egestas ultrices. Morbi pretium vulputate odio, a dictum diam pretium nec. Integer scelerisque dictum ante non tempor. Nullam convallis suscipit ligula et imperdiet. Curabitur malesuada molestie erat sit amet adipiscing. Quisque malesuada lobortis urna, porta ullamcorper tellus mollis vel. Aliquam sagittis, sem vel laoreet imperdiet, nisi lacus pretium ante, hendrerit faucibus massa lectus sed justo. Nunc et felis ipsum, nec fermentum diam.',
			'Integer sit amet mauris et sapien auctor sodales. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras placerat facilisis mattis. Vestibulum mauris elit, fermentum a tincidunt nec, gravida ac massa. Aenean hendrerit elementum justo, et interdum eros dapibus at. Duis rutrum, nisl sed aliquet mattis, metus massa suscipit odio, ut volutpat quam diam non elit. Maecenas dapibus, metus et fringilla commodo, arcu turpis volutpat purus, sit amet lobortis ligula justo in dolor. Praesent suscipit lacinia tincidunt. Aliquam vestibulum dolor sit amet tortor condimentum ac ultricies sapien tincidunt. Fusce mattis dapibus scelerisque. Duis pretium ullamcorper erat, in molestie nisl luctus sit amet.',
			'Phasellus vehicula lacus eu diam aliquet adipiscing. Vestibulum sed varius diam. Sed commodo faucibus enim eget facilisis. Aliquam sed ligula sem, adipiscing scelerisque tortor. Curabitur mollis, eros eget commodo luctus, augue elit consectetur ligula, et tempus nibh ante vitae nisi. Donec sed luctus enim. Mauris vel tellus a nunc lacinia varius. Maecenas ac turpis sapien. Maecenas sagittis varius magna, id ultricies ante pellentesque nec. Mauris et enim nec enim consequat pellentesque semper sit amet eros. Aliquam quis feugiat risus.',
			'Vivamus vulputate erat eget mi congue vestibulum. Nam ut accumsan dolor. Nulla facilisi. Etiam quam urna, laoreet eget mollis eget, porta sit amet felis. In aliquet magna eu mi consectetur eget semper magna interdum. Nullam quam neque, suscipit ac tempus at, placerat ut mauris. Nullam suscipit orci felis, faucibus hendrerit felis. Suspendisse quis quam felis.',
			'Donec a velit justo, in tempor urna. Integer mauris nisi, varius quis auctor eget, tincidunt non magna. Suspendisse potenti. Phasellus sit amet mauris turpis. Nulla facilisi. Integer eleifend eleifend leo, vel sagittis leo vulputate vel. In tortor est, fringilla sagittis ultrices non, fermentum ac ipsum. In hac habitasse platea dictumst. Vivamus posuere sagittis adipiscing. Nulla facilisi. Proin accumsan felis eu velit egestas cursus. Donec dapibus cursus massa, quis fermentum lectus rhoncus sit amet. Sed nisl nisl, egestas vel feugiat nec, mattis nec lacus. Phasellus euismod iaculis quam. Donec id ante et elit laoreet suscipit.',
			'Nulla mi ante, molestie congue lobortis vel, iaculis ac arcu. Phasellus ac consectetur velit. Suspendisse id nisi vitae ipsum dignissim dapibus vel at elit. Maecenas porta, nisl quis dapibus sagittis, est ipsum rutrum dolor, at lobortis velit lectus vitae felis. Curabitur nec ligula sed arcu feugiat fringilla at non nisl. In odio dui, tempor non suscipit sit amet, tristique et diam. Curabitur sit amet vestibulum sem. Aenean enim turpis, vestibulum eu scelerisque sed, tincidunt eget neque. Morbi eu tortor purus, nec cursus metus. Sed et ultricies nunc. In hac habitasse platea dictumst. Vivamus est eros, sodales in malesuada in, blandit ac tellus.',
			'Morbi libero augue, placerat suscipit rutrum in, suscipit sit amet nisl. Nulla et sollicitudin ante. Aliquam erat volutpat. Maecenas molestie sem quis justo lobortis eu euismod mi feugiat. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec sit amet libero ut velit laoreet iaculis. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
			'Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras hendrerit, turpis nec volutpat euismod, dui metus adipiscing magna, eu tristique leo risus ac neque. Nunc massa urna, posuere at lobortis sed, gravida ut massa. Curabitur ac diam erat, in viverra velit. Morbi nec dolor sit amet purus vulputate mattis. Integer dictum ultrices fermentum. Aliquam vel sapien vulputate sapien porta consequat vel et urna. Integer nec nunc vel turpis egestas tristique. Etiam sit amet pretium eros. Nulla mollis nunc a nibh fermentum a ultricies risus ornare.',
			'Mauris cursus pellentesque fermentum. Fusce sed est et velit feugiat lacinia. Cras sed eros a ante laoreet mollis sed ac quam. Fusce dictum accumsan volutpat. Suspendisse cursus dignissim leo, a ornare nisi convallis quis. Aliquam augue odio, eleifend eu molestie faucibus, placerat sed dolor. Fusce id risus vel ipsum auctor auctor in nec ipsum. Nunc augue eros, egestas at egestas vel, pharetra vel nisi. Pellentesque quis erat turpis, in volutpat turpis.',
			'Proin eu iaculis diam. Nunc sed molestie elit. Nulla aliquet sapien eget odio varius luctus. Proin risus enim, tempor ut vestibulum sed, viverra eleifend tellus. Vestibulum mattis eros vitae magna interdum et molestie ligula tristique. Sed at tellus diam. Donec vitae consectetur mauris. Pellentesque porttitor, lorem non porta semper, sapien massa hendrerit massa, eget feugiat tortor magna nec lectus. Morbi pretium risus eu nunc mattis in lobortis massa molestie.',
			'Etiam aliquet euismod venenatis. Pellentesque ut diam eu lacus tincidunt varius. Proin et odio quis eros gravida sagittis eget eget neque. In consectetur sapien sed nulla pretium hendrerit. Donec ac sem velit. Fusce vestibulum tristique lectus eget eleifend. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
			'Fusce tempor tincidunt enim, sit amet pretium augue suscipit ut. Vivamus eu elit dui, quis fermentum orci. Mauris adipiscing odio ultrices diam lacinia dictum. Donec a justo sit amet urna aliquet consectetur. Aenean et felis purus. Phasellus aliquet nibh sed leo tempus a consequat diam lobortis. Vestibulum ac nunc lectus, sit amet molestie est. Cras imperdiet pulvinar molestie. Vivamus tempor luctus luctus. Donec tincidunt felis nunc. Sed eget diam odio, eget commodo dolor.',
			'Aenean luctus odio sed orci mollis semper. Nulla fermentum lacus at odio commodo vitae ullamcorper leo dictum. Suspendisse rutrum condimentum lectus vel commodo. Pellentesque sed quam ligula, eget pharetra neque. Maecenas velit risus, suscipit non tristique at, mollis at odio. Etiam eget dolor metus. Sed et dictum odio. Nulla faucibus eros vel justo consectetur scelerisque. Nulla facilisi. Maecenas ac tincidunt arcu. Suspendisse potenti. Vestibulum ligula elit, dapibus vitae ultrices ut, feugiat vel lorem. Proin ultrices augue ut erat congue condimentum. Donec volutpat ante porta libero accumsan ac elementum tortor rhoncus. Sed nisl odio, fringilla id mollis at, porta aliquet arcu. In dictum enim a ipsum porttitor egestas faucibus massa tempor.',
			'Sed semper tellus sollicitudin libero congue in egestas metus pulvinar. Integer tempor, nunc nec pretium vulputate, ipsum ipsum tempor sapien, in malesuada neque lacus nec dolor. Fusce dapibus lorem ac nulla blandit ornare. Nunc egestas sapien at mi euismod eu suscipit dui mattis. Sed vel risus diam, eget euismod tellus. Nam ut enim orci, ac iaculis nisl. In consectetur egestas ullamcorper. Fusce id euismod justo. Donec hendrerit semper enim eu viverra. Sed vulputate accumsan leo id vehicula. Mauris ac nulla mauris.',
			'Aliquam erat volutpat. In odio dolor, sollicitudin ut porttitor et, consectetur ut nunc. Aliquam vitae tellus sed purus scelerisque pellentesque. Donec molestie feugiat sapien, ac ornare tortor egestas sit amet. Nulla facilisi. Integer sed tortor elit. Donec porta tellus vitae libero molestie vel hendrerit sapien iaculis. Aliquam suscipit elementum consequat. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
			'Sed pellentesque luctus feugiat. Fusce tortor elit, aliquam eu pulvinar a, tempus eget augue. Sed ac mi in sem placerat aliquet vitae quis nunc. In consectetur dolor eget tellus gravida ac fermentum enim tincidunt. Quisque consectetur nisl at dolor feugiat dapibus. Suspendisse mollis auctor massa interdum vestibulum. Duis commodo condimentum aliquam. Vivamus id dolor nisl. Proin sit amet ante at lectus gravida bibendum eget non dolor.',
			'Suspendisse ligula magna, sollicitudin eu vulputate sit amet, sollicitudin in tortor. Donec metus enim, consectetur eu commodo quis, ullamcorper et lacus. Aenean eget justo nulla. Proin tristique egestas eros placerat suscipit. Quisque metus ante, consequat sed tristique consectetur, iaculis at nunc. Curabitur et nunc a dui congue porta dictum a felis. Pellentesque ac mi ut dui venenatis pellentesque sed rhoncus tortor. Duis sagittis, est mattis gravida luctus, eros leo hendrerit nulla, in pellentesque turpis dolor vitae diam. Fusce convallis sagittis nibh, vitae pretium orci aliquet facilisis. Nunc quis placerat arcu. Aenean quis ullamcorper elit. Nulla nec nibh nec sem tristique vehicula et vitae lectus. Praesent placerat elementum neque, ac accumsan enim semper sed.',
			'Aliquam ut placerat tellus. Donec ut eros augue, id tincidunt lacus. Praesent hendrerit, erat sed venenatis pharetra, felis diam tincidunt justo, a sodales purus lectus vel tellus. Donec in magna leo. Maecenas lacinia dictum arcu, in tincidunt lorem molestie fermentum. Donec blandit, dolor vitae commodo varius, elit metus egestas quam, in ultrices magna purus ac nisl. Sed volutpat egestas facilisis. Aliquam vitae lacus orci, ac mollis dui. Aenean at nibh sed mauris vulputate porta. Cras sed lacus vitae nunc blandit lacinia ut non nulla.',
			'Cras arcu ante, congue id sodales sollicitudin, consequat sed quam. Proin a enim vitae dui viverra semper. Duis est justo, laoreet ac pharetra eget, vehicula at augue. Integer ac erat urna, at mollis tortor. Proin congue velit quis lacus bibendum egestas. In sed neque eu nisi tempor hendrerit volutpat quis mi. In sit amet nulla dui.',
			'Cras id tellus id risus fermentum venenatis sed vitae nibh. Sed libero enim, convallis in posuere eget, viverra in elit. Integer eu libero odio. Curabitur blandit, orci ac molestie accumsan, lacus lectus lacinia dui, non malesuada ipsum dui sed nibh. Donec ut nunc rhoncus lectus lacinia cursus. Nullam ullamcorper blandit aliquam. Vivamus lobortis elit mauris, in pellentesque metus. Praesent posuere neque vel ligula lacinia tempor. Fusce eleifend, tortor id tristique aliquam, lectus velit mattis dui, sed posuere nisi felis nec tellus. Etiam vel elit justo. Sed at eros sit amet tellus tristique suscipit nec quis sapien. Aliquam quis ipsum nunc. Donec at sem felis, a gravida libero. Cras in est nisl, ac molestie arcu.',
			'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce elit enim, tincidunt ut auctor sed, imperdiet et turpis. Quisque mattis, diam nec tempus molestie, nisl est varius tellus, a consectetur neque arcu eu ligula. Maecenas posuere mollis massa sit amet interdum. Quisque laoreet turpis a lorem viverra consequat. Etiam gravida diam in urna varius scelerisque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut vitae quam felis. Nullam nec nunc quam, quis commodo nunc. Vestibulum quis risus in nibh bibendum gravida. Donec vel felis sit amet enim dictum rutrum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Etiam dictum eros eu sem hendrerit sollicitudin. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aliquam erat volutpat.',
			'Quisque sit amet velit mauris, eu congue elit. Nam non mi vitae felis viverra ultricies quis ut ante. Aliquam rhoncus semper magna a gravida. Sed aliquam enim ac massa varius viverra. Donec at nibh vel augue dignissim porta. Proin sagittis, lacus nec tincidunt feugiat, dui felis consectetur nisi, at mattis leo felis ut metus. Praesent magna elit, placerat sed tempor eget, mollis ac lectus.',
			'In tortor mauris, dictum at iaculis sit amet, bibendum vitae dui. Sed condimentum est sed tortor interdum pharetra. Phasellus ornare, massa fringilla placerat porta, leo massa dictum lectus, facilisis mattis felis nulla sed urna. Aliquam pharetra, lectus at mattis scelerisque, ipsum arcu gravida lectus, eget tempus enim ante in diam. Fusce nunc nulla, luctus vitae ultrices vel, varius eu dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla id condimentum nisi. Sed scelerisque adipiscing odio non ullamcorper. In hac habitasse platea dictumst. Sed dictum risus iaculis mauris gravida a placerat nisi gravida. Sed non libero justo. Cras vehicula metus sit amet quam bibendum non varius dolor viverra. Aenean et turpis sed mi luctus ornare. Nulla nec turpis in elit bibendum dictum.',
			'Sed fermentum dictum purus, eget imperdiet nibh interdum ac. Fusce mattis sollicitudin arcu condimentum egestas. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla dolor lectus, aliquet sed aliquet at, laoreet in sapien. Morbi bibendum tempus tincidunt. Sed luctus lorem eget neque cursus ultricies porta eros ornare. Etiam tristique velit a lacus lacinia non ultrices neque faucibus. Aenean eget mauris eget lacus egestas pretium.',
			'Etiam lorem turpis, condimentum eget venenatis non, lacinia ut lectus. Donec odio magna, volutpat ut porta sed, euismod ut nisl. Donec quis nunc ut ante facilisis blandit. Aenean in tortor risus. Donec sodales pellentesque venenatis. Nunc rhoncus tempor tellus at pellentesque. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed a orci in quam blandit accumsan. Vivamus ac quam vel libero sollicitudin tempor.',);
	
	public static function generateLoremIpsumLetter($letter) {
		$lipsum = self::$lorem;
		$sentence = $lipsum[rand(1, count($lipsum))];
		return substr($sentence, $letter);
	}
	
	public static function generateLoremIpsumWord($word) {
		$lipsum = self::$lorem;
		$sentence = $lipsum[rand(1, count($lipsum))];
		return array_slice(explode(' ', $sentence), 0, $word);
	}
	
	/**
	 * Generates Lorem Ipsum
	 * @param number $paragraph
	 */
	public static function generateLoremIpsum($paragraph = 1) {
		$size_of_lorem = count(self::$lorem);
		if ($paragraph > $size_of_lorem)
			die('available size of lorem ' . $size_of_lorem);

		return implode('<p/>', array_slice(self::$lorem, 0, $paragraph));
	}

	public static function encrypt($key, $string) {
		return base64_encode(
				mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string,
						MCRYPT_MODE_CBC, md5(md5($key))));
	}

	public static function decrypt($key, $encrypted) {
		return rtrim(
				mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key),
						base64_decode($encrypted), MCRYPT_MODE_CBC,
						md5(md5($key))), "\0");
	}

	static public function ___slugify($text) {
		// replace non letter or digits by -
		$text = preg_replace('#[^\\pL\d]+#u', '-', $text);

		// trim
		$text = trim($text, '-');

		// transliterate
		if (function_exists('iconv')) {
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		}

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('#[^-\w]+#', '', $text);

		if (empty($text)) {
			return 'n-a';
		}

		return $text;
	}

	/**
	 * Create a web friendly URL slug from a string.
	 *
	 * Although supported, transliteration is discouraged because
	 *     1) most web browsers support UTF-8 characters in URLs
	 *     2) transliteration causes a loss of information
	 *
	 * @author Sean Murphy <sean@iamseanmurphy.com>
	 * @copyright Copyright 2012 Sean Murphy. All rights reserved.
	 * @license http://creativecommons.org/publicdomain/zero/1.0/
	 *
	 * @param string $str
	 * @param array $options
	 * @return string
	 */
	static public function slugify($str, $options = array()) {
		// Make sure string is in UTF-8 and strip invalid UTF-8 characters
		$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	
		$defaults = array(
				'delimiter' => '-',
				'limit' => null,
				'lowercase' => true,
				'replacements' => array(),
				'transliterate' => false,
		);
	
		// Merge options
		$options = array_merge($defaults, $options);
	
		$char_map = array(
				// Latin
				'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
				'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
				'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
				'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
				'ß' => 'ss',
				'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
				'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
				'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
				'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
				'ÿ' => 'y',
	
				// Latin symbols
				'©' => '(c)',
	
				// Greek
				'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
				'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
				'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
				'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
				'Ϋ' => 'Y',
				'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
				'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
				'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
				'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
				'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
	
				// Turkish
				'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
				'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
	
				// Russian
				'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
				'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
				'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
				'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
				'Я' => 'Ya',
				'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
				'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
				'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
				'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
				'я' => 'ya',
	
				// Ukrainian
				'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
				'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
	
				// Czech
				'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
				'Ž' => 'Z',
				'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
				'ž' => 'z',
	
				// Polish
				'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
				'Ż' => 'Z',
				'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
				'ż' => 'z',
	
				// Latvian
				'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
				'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
				'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
				'š' => 's', 'ū' => 'u', 'ž' => 'z'
		);
	
		// Make custom replacements
		$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	
		// Transliterate characters to ASCII
		if ($options['transliterate']) {
			$str = str_replace(array_keys($char_map), $char_map, $str);
		}
	
		// Replace non-alphanumeric characters with our delimiter
		$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	
		// Remove duplicate delimiters
		$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	
		// Truncate slug to max. characters
		$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	
		// Remove delimiter from ends
		$str = trim($str, $options['delimiter']);
	
		return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}
	
	public static function randomUniqueString($length = 10) {
		return strtoupper(
				substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0,
						$length));
	}

	public function randomStringGenerator($length = 10, $alphanumeric = false) {
		$pattern = "1234567890";
		if ($alphanumeric)
			$pattern .= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for ($i = 0; $i < $length; $i++) {
			$s .= $pattern{rand(0, strlen($pattern) - 1)};
		}
		return $s;
	}

	public function randomColorGenerator() {
		global $red, $green, $blue, $color;
		$color = array(rand(50, 255), rand(50, 255), rand(50, 255)); //generates random RGB color;
		$red = $color[1]; //red value;
		$green = $color[2]; //green value;
		$blue = $color[3]; //blue value;
	}

	public function clearWhiteSpaces($str) {
		return preg_replace("/[[:space:]]/", "", $str);
	}

	// TODO Not completed
	public function data_url($file, $mime) {
		// TODO Resim alınabiliyor mu denenecek
		// http://en.wikipedia.org/wiki/Data:_URI
		// NOT WORKS WITH IE
		/* <img src="<?php echo data_url('elephant.png','image/png')?>" alt="An elephant" /> */
		/*window.open('data:text/html;charset=utf-8,%3C!DOCTYPE%20HTML%20PUBLIC%20%22-'+
		 '%2F%2FW3C%2F%2FDTD%20HTML%204.0%2F%2FEN%22%3E%0D%0A%3Chtml%20lang%3D%22en'+
		 '%22%3E%0D%0A%3Chead%3E%3Ctitle%3EEmbedded%20Window%3C%2Ftitle%3E%3C%2Fhea'+
		 'd%3E%0D%0A%3Cbody%3E%3Ch1%3E42%3C%2Fh1%3E%3C%2Fbody%3E%0D%0A%3C%2Fhtml%3E'+
		 '%0D%0A','_blank','height=300,width=400');
		 */
		$pages = file_get_contents($file);
		$base64 = base64_encode($pages);
		return ('data:' . $mime . ';base64,' . $base64);
	}

	/*
	 * strtoupper(): ıabc idef ghıiş » IABC İDEF GHIİŞ
	 */
	function trbuyult($veri) {
		return mb_convert_case(str_replace('i', 'İ', $veri), MB_CASE_UPPER,
				"UTF-8");
	}

	/*
	 * strtolower(): strtoupper(): IABC İDEF GHIİŞ » ıabc idef ghıiş
	 */
	function trkucult($veri) {
		return mb_convert_case(str_replace('I', 'ı', $veri), MB_CASE_LOWER,
				"UTF-8");
	}

	/*
	 * ucwords(): ıabc idef ghıiş » Iabc İdef Ghıiş
	 */
	function trkelilk($veri) {
		return mb_convert_case(
				str_replace(array(' I', ' ı', ' İ', ' i'),
						array(' I', ' I', ' İ', ' İ'), $veri), MB_CASE_TITLE,
				"UTF-8");
	}

	/*
	 * ucfirst(): ıabc idef ghıiş » Iabc idef ghıiş veya IABC İDEF GHIİŞ » Iabc idef ghıiş
	 */
	function trcumilk($veri) {
		$veri = in_array(crc32($veri[0]),
				array(1309403428, -797999993, 957143474)) ? array(
						trbuyult(substr($veri, 0, 2)),
						trkucult(substr($veri, 2)))
				: array(trbuyult($veri[0]), trkucult(substr($veri, 1)));
		return $veri[0] . $veri[1];
	}

	function getCurrentPath() {
		$a = explode("/", $_SERVER["REQUEST_URI"]);
		array_pop($a);
		return implode("/", $a);
	}

	/*
	 * Reads the extension of the file
	 *
	 * @param string $filename the the filename with extension
	 */
	static public function getExtension($filename) {
		// 1. The "explode/end" approach
		//$ext = end(explode('.', $filename));
		// 2. The "strrchr" approach
		//$ext = substr(strrchr($filename, '.'), 1);
		// 3. The "strrpos" approach
		//$ext = substr($filename, strrpos($filename, '.') + 1);
		// 4. The "preg_replace" approach
		//$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $filename);
		// 5. The "pathinfo" approach
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		/*
		// OLD STYLE
		$i = strrpos($filename, ".");
		if (!$i) {
			return "";
		}
		$l = strlen($filename) - $i;
		$ext = substr($filename, $i + 1, $l);
		*/
		
		return strtolower($ext);
	}

	function getExtensionReturnArray($filename) {
		return explode(".", $filename);
	}

	/*
	 * kimlik numaraları 11 haneden oluşur
	 * her bir hane rakamsal değer içerir
	 * 0 (sıfır) ile başlamazlar
	 * 1, 3, 5, 7 ve 9. basamakların toplamının 7 katından, 2, 4, 6 ve 8. basamakların toplamı çıkartıldığında, çıkan sonucun 10′a bölümünden kalan sayı (mod10), kimlik numaramızın 10. hanesine eşittir
	 * ilk 10 basamağın toplamından çıkan sonucun 10′a bölünmesinden kalan sayı da, 11. haneye eşittir.
	 *
	 */
	static public function isTckimlik($tckimlik) {
		$olmaz = array('11111111110', '22222222220', '33333333330',
				'44444444440', '55555555550', '66666666660', '77777777770',
				'88888888880', '99999999990');

		if ($tckimlik[0] == 0 or !ctype_digit($tckimlik)
				or strlen($tckimlik) != 11) {
			return false;
		} else {
			for ($a = 0; $a < 9; $a = $a + 2) {
				$ilkt = $ilkt + $tckimlik[$a];
			}
			for ($a = 1; $a < 8; $a = $a + 2) {
				$sont = $sont + $tckimlik[$a];
			}
			for ($a = 0; $a < 10; $a = $a + 1) {
				$tumt = $tumt + $tckimlik[$a];
			}

			if (($ilkt * 7 - $sont) % 10 != $tckimlik[9]
					or $tumt % 10 != $tckimlik[10]) {
				return false;
			} else {
				foreach ($olmaz as $olurmu) {
					if ($tckimlik == $olurmu) {
						return false;
					}
				}
				return true;
			}
		}
	}

	/*
	 * http://www.webcheatsheet.com/php/regular_expressions.php
	 */
	public static function checkStartsEndsWith($needle, $haystack) {
		return preg_match('/(^' . $needle . '|' . $needle . '$)/', $haystack);
	}

	public static function getIP() {
		$ip = null;
		if (isset($_SERVER["REMOTE_ADDR"])) {
			$ip = $_SERVER["REMOTE_ADDR"];
		} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		return $ip;
	}

	/*
	 * $number = 1234.56;
	 * setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
	 * setlocale(LC_ALL, 'tr_TR.UTF-8', 'tr_TR', 'tr', 'turkish');
	 * echo ARIPDString::money_format('%.2n', $number) . "\n";
	 * 
	 */
	function money_format($format, $number) {
		if (function_exists('money_format')) {
			return money_format($format, $number);
		}
		if (setlocale(LC_MONETARY, 0) == 'C') {
			setlocale(LC_MONETARY, '');
			//return number_format($number, 2);
		}

		$locale = localeconv();

		$regex = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'
				. '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';

		preg_match_all($regex, $format, $matches, PREG_SET_ORDER);

		foreach ($matches as $fmatch) {
			$value = floatval($number);
			$flags = array(
					'fillchar' => preg_match('/\=(.)/', $fmatch[1], $match) ? $match[1]
							: ' ',
					'nogroup' => preg_match('/\^/', $fmatch[1]) > 0,
					'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? $match[0]
							: '+',
					'nosimbol' => preg_match('/\!/', $fmatch[1]) > 0,
					'isleft' => preg_match('/\-/', $fmatch[1]) > 0);
			$width = trim($fmatch[2]) ? (int) $fmatch[2] : 0;
			$left = trim($fmatch[3]) ? (int) $fmatch[3] : 0;
			$right = trim($fmatch[4]) ? (int) $fmatch[4]
					: $locale['int_frac_digits'];
			$conversion = $fmatch[5];

			$positive = true;
			if ($value < 0) {
				$positive = false;
				$value *= -1;
			}
			$letter = $positive ? 'p' : 'n';

			$prefix = $suffix = $cprefix = $csuffix = $signal = '';

			$signal = $positive ? $locale['positive_sign']
					: $locale['negative_sign'];
			switch (true) {
			case $locale["{$letter}_sign_posn"] == 1
					&& $flags['usesignal'] == '+':
				$prefix = $signal;
				break;
			case $locale["{$letter}_sign_posn"] == 2
					&& $flags['usesignal'] == '+':
				$suffix = $signal;
				break;
			case $locale["{$letter}_sign_posn"] == 3
					&& $flags['usesignal'] == '+':
				$cprefix = $signal;
				break;
			case $locale["{$letter}_sign_posn"] == 4
					&& $flags['usesignal'] == '+':
				$csuffix = $signal;
				break;
			case $flags['usesignal'] == '(':
			case $locale["{$letter}_sign_posn"] == 0:
				$prefix = '(';
				$suffix = ')';
				break;
			}
			if (!$flags['nosimbol']) {
				$currency = $cprefix
						. ($conversion == 'i' ? $locale['int_curr_symbol']
								: $locale['currency_symbol']) . $csuffix;
			} else {
				$currency = '';
			}
			$space = $locale["{$letter}_sep_by_space"] ? ' ' : '';

			$value = number_format($value, $right,
					$locale['mon_decimal_point'],
					$flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
			$value = @explode($locale['mon_decimal_point'], $value);

			$n = strlen($prefix) + strlen($currency) + strlen($value[0]);
			if ($left > 0 && $left > $n) {
				$value[0] = str_repeat($flags['fillchar'], $left - $n)
						. $value[0];
			}
			$value = implode($locale['mon_decimal_point'], $value);
			if ($locale["{$letter}_cs_precedes"]) {
				$value = $prefix . $currency . $space . $value . $suffix;
			} else {
				$value = $prefix . $value . $space . $currency . $suffix;
			}
			if ($width > 0) {
				$value = str_pad($value, $width, $flags['fillchar'],
						$flags['isleft'] ? STR_PAD_RIGHT : STR_PAD_LEFT);
			}

			$format = str_replace($fmatch[0], $value, $format);
		}
		return $format;
	}

	public function isGLN($number) {
		if ($number == null || $number == "" || strlen($number) != 13)
			return false;

		$checkDigit = substr($number, 12, 1);
		$number = substr($number, 0, 12);
		$temp = str_split($number);

		$even = 0;
		$odd = 0;
		foreach ($temp as $k => $v) {
			if ($k % 2 == 0) {
				$even += $v;
			} elseif ($k % 2 == 1) {
				$odd += $v;
			}
		}

		$remainder = ($even + ($odd * 3)) % 10;
		if ($remainder > 0) {
			$calculation = 10 - $remainder;
		} else {
			$calculation = $remainder;
		}

		if ($calculation == $checkDigit) {
			return true;
		} else {
			return false;
		}
	}

}
