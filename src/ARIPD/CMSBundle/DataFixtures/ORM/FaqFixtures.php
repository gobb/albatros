<?php
namespace ARIPD\CMSBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use ARIPD\CMSBundle\Entity\Faq;
use ARIPD\AdminBundle\Util\ARIPDString;

class FaqFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	protected $initials = array(
			array(
					'İade Bilgileri',
					'
<p>Aldığınız her ürün, üretici firmasının garantisi altındadır.</p>
<p>Almış olduğunuz ürününü ambalajını açmadan, tahrip etmeden, bozmadan, ürünü kullanmadan teslim tarihinden itibaren yedi (7) günlük süre içinde teslim aldığınız şekli ile iade edebilirsiniz. Ürünü, ürün faturası, sipariş numarınızıda içeren bir dilekçe veya iade formu ile iade ediniz.</p>
<p>Sevkiyat sırasında zarar gördüğünü düşündüğünüz paketleri teslim aldığınız firma yetkilisi önünde açıp kontrol ediniz. Eğer üründe herhangi bir zarar varsa kargo firmasına tutanak tuttururarak ürünü teslim almayınız. Ürün teslim alındıktan sonra kargo firmasının görevini tam olarak yerine getirdiğini kabul etmiş olduğunuzu unutmayınız.</p>
<p>Ambalajı açılmış, kullanılmış, tahrip edilmiş vesair şekildeki ürünlerin iadesi kabul edilmez.</p>
<p>Müşteri ürünü, kendisine teslim edildiği andaki durumu ile geri vermekle ve kullanım söz konusu ise kullanma dolayısıyla malın ticari değerindeki kaybı tazminle yükümlüdür.</p>
<p>Müşterinin ürünü iade etmesi veya alışverişinden cayması halinde, iade edilen ürün Bize ulaştığı andan itibaren on (10) gün içerisinde ürün bedeli iade edilir. Kredi kartına ürün iade bedeli bankanız tarafından 2-6 hafta arasında yapılmaktadır. Bu sürede firmamızın tasarrufu bulunmamaktadır.</p>
<p>Üründe ve ambalajında herhangi bir açılma, bozulma, kırılma, tahrip, yırtılma, kullanılma ve sair durumları tespit ettiği hallerde ve ürünün müşteriye teslim edildiği andaki hali ile iade edilememesi halinde ürünü iade almayacak ve bedelini iade etmeyecektir.</p>
<p>Ürün müşteriye ulaştıktan sonra ortaya çıkabilecek arızalar için, üretici firmanın yetkili servislerine başvurulmalıdır.</p>
<p>Yukarıdaki şartlara uygun hallerde iade edilen ürünlerde kargo ücreti müşteri tarafından ödenecektir.</p>
<p>Tarafımıza Bildirilmeden Gönderilen iade ürünler için Hiç bir işlem yapılmadan gönderen kişiye geri iadesi yapılır.</p>
<p>Havale iadeleri 2 iş günü içinde Kredi Kartı iadeleri 3 iş günü içinde yapılacaktır. Bankanız kredi kartı iadelerini aynı gün hesabınıza yansıtmayabilir. Bu durumda bankanızın kredi kartı servisini aramanız gereklidir. Siparişinizle ilgili “İptal Edildi” uyarısı çıktıktan sonra tüm bedel kredi kartınıza veya havale yaptığınız bankanıza iade edilmektedir. Taksitli satışlarda yapılan iadeler bankanız tarafından kredi kartınıza her ay artı bakiye olarak yansıtılmaktadır.</p>
					',
			),
			array(
					'Alışveriş Prosedürü',
					'
<p>Elektronik alışveriş yapma adımları:</p>
<p>1. Öncelikle sitemize üye olmanız gerekmektedir. Üye olabilmek için "ÜYE OLUN" veya "Yeni Üyelik İçin Tıklayın" linklerini kullanabilirsiniz.</p>
<p>2. Almak istediğiniz ürünleri miktarını belirledikten sonra "Sepete Ekle" butonuna tıklayarak alışveriş sepetinize atabilirsiniz. Alışveriş sepetinin amacı, müşterinin seçer seçmez satın alma zorunluluğunu ortadan kaldırarak kolaylık sağlamaktır.</p>
<p>3. Alışveriş Sepetinizde ürünle ilgili fiyat ve diğer bilgileri görebilir. Ayrıca, ürünlerin miktarlarını değiştirebilir, sepetinizden çıkarabilirsiniz. Ürün fiyatları ve tutar bilgilerine KDV dahildir.</p>
<p>4. Sepetinizdeki ürünleri 4 farklı şekilde alabilirsiniz. Kredi kartı ile peşin ödeme , kredi kartı ile taksitli ödeme, havale ile indirimli ödeme, Cash on Delivery ( Teslimatta ödeme ). Ödeme şeklini seçtikten sonra seçtiğiniz ödeme şekline göre detay ekranı gelmektedir. İstenilen verileri girdikten sonra "Satın Al" butonuna tıklayın.</p>
<p>5. Eğer ödemeyi kredi kartı ile yapacaksanız; siparişi onayladığınız anda, bilgiler net üzerinden özel bir geçiş yoluyla ödemeyi yapan veya ödemeyi teslim alan bankaların bu işlemi onayladıkları veya reddettikleri network işleme mekanizmasına (SSL) ulaşır. Kredi kartı bilgileriniz geçerli ise siparişiniz kabul edilir. Aldığığınız ürünlerin ücreti banka yoluyla bize ulaşır. Sipariş olumlu bir şekilde tamamlandığı taktirde, müşteriye siparişiyle ilgili bilgilerin yer aldığı bir e-mail gönderilir. Satın alınan ürün, önceden anlaşılmış olan güvenilir bir kargo şirketlerinden müşterinin seçtiği biri tarafından, siparişi teslim alacak kişiye kimlik gösterilmesi karşılığında teslim edilir.</p>
<p>6. Bu işlemler sonunda, sistem size otomatik olarak bir sipariş numarası verecektir ve sipariş izleme modülüne yönlendirecektir. Bu numara, daha sonra ödeme ve sipariş takip işlemlerinin gerçekleştirilmesi için mutlaka kaydedilmelidir.</p>
<p>7. Sipariş izleme modülü yardımıyla yaptığınız siparişlerin durumunu istediğiniz zaman kontrol edebilirsiniz.</p>
					',
			),
			array(
					'Sipariş Takibi',
					'
<p>Online olarak sitemizden verilen siparişler sipariş formunda belirtilen zaman toleransı içinde periyodik olarak hergün , gün içinde belli saatlerde kontrol edilmektedir. Saat 17:00 den sonra verilen siparişler ertesi gün değerlendirilir.</p>
<p>Sipariş transferi paralel olarak kullanılan alternatif yöntemlerle gerçekleştirilmiktedir.</p>
<p>Sipariş formumuz aracılığı ile tarafımıza iletmiş olduğunuz sipariş bilgileri, kredi kartı bilgileriniz hariç olmak üzere veritabanımıza kaydedilmektedir ve ek olarak hem işyerimize hem hizmet sunucumuza e-posta yolu ile aktarılmaktadır.</p>
<p>Veritabanı veya e-posta sistemlerinde meydana gelebilecek olası herhangi bir soruna karşı veritabanımız ve e-posta kutularımız hem tarafımızca hem hizmet sunucumuz tarafından periyodik olarak kontrol edilmektedir.</p>
<p>Sipariş sistemimiz herhangi bir sorun nedeniyle siparişinizi tarafımıza iletilemiyorsa ziyaretçi sipariş sırasında durumdan haberdar edilmektedir.</p>
					',
			),
			array(
					'Ödeme Takibi',
					'
<p>Kredi kartı aracılığı ile gerçekleştirilen ödeme Garanti Bankası, Yapı Kredi E-Ticaret sistemi aracılığı ile online olarak gerçekleştirilmektedir.</p>
<p>Sözkonusu sistem herehangi bir sorun nedeni ile işlemi gerçekleştiremiyorsa ödeme sayfası sonucunda ziyaretçimiz bu durumdan haberdar edilmektedir.</p>
<p>USD fiyatlarımız banka POS sistemine TL olarak aktarılmaktadır ve bu işlem için kullanılan kur periyodik olarak güncellenmektedir ve kur dalgalanmalarından doğan farklılığın kredi kartı sahibinin lehine olması için TCMB bankası kurunun altında kalacak biçimde kur güncellenmektedir.</p>
					',
			),
			array(
					'Teslimat Takibi',
					'
<p>Sipariş formumuzda teslimat için izin verdiğimiz zaman, teslimatı gerçekleştirebileceğimiz zamana göre %100 oranında toleranslıdır.</p>
<p>Belirtilen adreste herhangi bir hata durumunda teslimatı gerçekleşemeyen sipariş ile ilgili olarak siparişi veren ile bağlantı kurulmaktadır.</p>
<p>Ziyaretçimiz tarafından belirtilen e-posta adresinin geçerliliği siparişin aktarılmasını takiben gönderilen otomatik e-posta ile teyid edilmektedir.</p>
<p>Teslimatın gerçekleşmesi konusunda müşteri kadar kredi kartı sistemini kullandığımız bankaya karşı da sorumluluğumuz sözkonusudur.</p>
<p><strong>Teslimat Yöntemi</strong></p>
<p>Kullanıcı teslimat yöntemi olarak 2 farklı yolu seçebilir. Şirket teslim ve kargo ile teslim şeklinde 2 yöntemden birini seçen kullanıcı alışveriş bedeline göre kargo ücretinden muaf tutulmaktadır.</p>
					',
			),
			array(
					'Ürün Garantisi',
					'
<p>Mağazamızdaki ürünler imalatçı veya ithalatçı firmaların garantisi altındadır ve her markanın kendi garanti koşulları geçerlidir.</p>
					',
			),
			array(
					'Güvenlik',
					'
<p>Sitemizde kredi kartı işlemi için haberleşmede her aşama güvenlik altına alınmıştır.</p>
<p>Site-Ziyaretçi Haberleşme Güvenliği</p>
<p>Sitemizin sipariş sayfalarında site ile ziyaretçi arasındaki haberleşme 128 bit SSL standartında gerçekleşmektedir. Sözkonusu haberleşme standardı çok sayıda işlem gören sitelerde dahi güvenle kullanılan bir niteliktedir. Kredi kartı bilgilerinin verileceği sayfada bu haberleşme biçiminin bulunup bulunmadığını, sayfaya erişildiğinde adres çubuğunda yazan ifadenin http://.. biçiminde değil, https://.. biçiminde oluşu ifade etmektedir. Bu nitelikteki sayfalara eriştiğinizde tarayıcının sağ alt köşesinde kilit işareti de yer almaktadır.</p>
<p>Site-Banka Haberleşme Güvenliği</p>
<p>Kredi kartı bilgilerinin siteden bankaya aktarılması ile ilgili güvenlik, bankanın sunduğu maksimum güvenlik ile gerçekleşmektedir. Sözkonusu güvenliğin çok sayıda bileşenin yanında, CVV2/CVC2 kodu da çalıntı kart veya kart bilgileri ile alışverişe karşı önlem olarak sitemizde kullanılmaktadır.</p>
<p>Site içi Veri Güvenliği</p>
<p>Güvenli ortamda yapacağınız işlemlerde siz ve kredi kartını size tahsis esen banka haricinde hiçbir kişi , kurum ve kuruluş tarafından bilgilerinize ulaşamamaktadır. Kredi kartı işlem sayfası kart bilgilerini doğrudan banka POS sistemine iletmekte ve işlem sonucunu müşteriye bildirmektedir. Kredi kartı bilgileri e-posta veya benzeri yöntemlerle aktarılmamaktadır. Online işlemin bir sonucu olarak aktarılan kredi kart bilgilerine tarafımızdan dahi erişilmesi mümkün değildir.</p>
					',
			),
			array(
					'Tüketicinin Korunması Hakkında Kanun',
					'
<p><a href="http://dl.dropbox.com/u/36591931/pdc_com_tr/file/TuketicininKorunmasi.doc">Tüketicinin Korunması Hakkında Kanun</a></p>
					',
			),
			array(
					'Mesafeli Sözleşmeler Uygulama Esasları Hakkında Yönetmelik',
					'
<p><a href="http://dl.dropbox.com/u/36591931/pdc_com_tr/file/MesafeliSozlesmeler.doc">Mesafeli Sözleşmeler Uygulama Esasları Hakkında Yönetmelik</a></p>
					',
			),
	);
		
	public function load(ObjectManager $manager) {
		foreach ( $this->initials as $initial) {
			$entity = new Faq();
			$entity->setIso639($manager->merge($this->getReference('aripdadmin_iso639-1')));
			$entity->setName($initial[0]);
			$entity->setContent($initial[1]);
			
			$manager->persist($entity);
		}

		$manager->flush();
	}

	public function getDependencies() {
		return array(
				'ARIPD\AdminBundle\DataFixtures\ORM\Iso639Fixtures',
		);
	}
		
}
