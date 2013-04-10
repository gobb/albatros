<?php
namespace ARIPD\IntranetBundle\DataFixtures\ORM;
use ARIPD\IntranetBundle\Entity\IPTV;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class IPTVFixtures extends AbstractFixture {
	
	public function load(ObjectManager $manager) {
		$initials = array(
			array('CNN Türk','CNNTurk3','rtmp://live.dtv.cubecdn.net:80/cnnturktv/','','','',''),
			array('Kanal Türk','KanalTurk3','rtmpt://94.75.221.227/iphone/','','','',''),
			array('Genç TV','BQ2HJKN','rtmp://212.175.65.69/live/','','','',''),
			array('Bugün TV','BugunTV3','rtmpt://94.75.221.227/iphone/','','','',''),
			array('Bloomberg HT','bloomberght1','rtmp://stream.ciner.com.tr/bloomberght/','','','',''),
			array('TGRT Haber','tgrt1','rtmp://strm-3.tr.medianova.tv/tgrt/','','','',''),
			array('NTV SPOR','ntvspor3','rtmp://strm-3.tr.medianova.tv/tv_ntvspor/','','','',''),
			array('Star TV','StarTV3','rtmp://live.dtv.cubecdn.net/kdmobil/','','','',''),
			array('Kral TV','kraltv_avrupa3','rtmp://dogus-live.mncdn.com/kraltv_avrupa/','http://mnhst.mncdn.net/players/dogus/flowplayer.commercial-3.2.8.swf','#$dcd04917446e61443ab','http://mnhst.mncdn.net/players/dogus/flowplayer.rtmp-3.2.3.swf','http://mnhst.mncdn.net/players/dogus/flowplayer.controls-3.2.9.swf'),
			array('HABERTÜRK','haberturk2','rtmp://live-ciner.mncdn.net/haberturk/','http://www.haberturk.com/images/flash/flowplayer.commercial-3.1.5.swf?v=1','#@da880a46c3de652e184','http://www.haberturk.com/images/flash/flowplayer.rtmp-3.1.3.swf?v=1',''),
			array('NTV','ntv3','rtmp://dogus-live.mncdn.com/tv_ntv/','http://mnhst.mncdn.net/players/dogus/flowplayer.commercial-3.2.10.swf','#$b73248ea38e3ec93b9a','http://mnhst.mncdn.net/players/dogus/flowplayer.rtmp-3.2.9.swf','http://mnhst.mncdn.net/players/dogus/flowplayer.controls-3.2.10.swf'),
			array('KanalD','KanalD1','rtmp://live.dtv.cubecdn.net/kdmobil/','','','',''),
			array('FOXTV','foxtv3','rtmp://strm-3.tr.medianova.tv/foxtv_web/','','','',''),
			array('Yumurcak TV','yumurcak3','rtmp://eu02.kure.tv:80/liveedge/','','','',''),
			array('Samanyolu TV','shaber3','rtmp://media2.kure.tv:1935/live/_definst_/','','','',''),
			array('Radyo Fresh','channel_13','rtmp://www.planeta-online.tv:1936/live/','','','',''),
			array('Fresh TV','channel_4','rtmp://www.planeta-online.tv:1936/live/','','','',''),
			array('ENTBAYTV','channel_21','rtmp://planetaonline.cdnvideo.ru/rr/','','','',''),
			array('Doctor Degree','channel_22','rtmp://planeta-online.tv:1936/live/','','','',''),
			array('Barbariki','channel_6','rtmp://www.planeta-online.tv:1936/live/','','','',''),
			array('Ulusal','test2','rtmp://stream1.yayinizle.com/test2/','','','',''),
			array('TV8','tv8_live3','rtmp://strm-3.tr.medianova.tv/tv8/','','','',''),
			array('Number1 TV','nr2','rtmp://94.23.248.192/live/','','','',''),
			array('ATV','atv3','rtmp://live.atv.com.tr:443/atv','','','',''),
			array('CNBC-e','cnbce3','rtmp://strm-3.tr.medianova.tv/tv_cnbce/','','','',''),
			array('Planet','','rtmp://93.187.203.125/live/planet4','','','',''),
			array('Karadeniz TV','karadeniz2','rtmp://karadeniztv.garantisistem.com:1935/karadeniztv/','','','',''),
			array('Kanal7','kanal7','rtmp://live.gostream.nl/','http://www.gostream.nl/zplayer/flowplayer.ozel.swf?0.11836627032607794','','http://www.gostream.nl/zplayer/flowplayer.rtmp-3.2.3.swf',''),
		);

		foreach ($initials as $initial) {
			$entity = new IPTV();
			$entity->setName($initial[0]);
			$entity->setClipurl($initial[1]);
			$entity->setNetconnectionurl($initial[2]);
			$entity->setWidth(800);
			$entity->setHeight($entity->getWidth()/1.6);
			$entity->setPlayersrc($initial[3]);
			$entity->setPlayerkey($initial[4]);
			$entity->setInfluxisurl($initial[5]);
			$entity->setControlsurl($initial[6]);
			$manager->persist($entity);
		}

		$manager->flush();
	}

}
