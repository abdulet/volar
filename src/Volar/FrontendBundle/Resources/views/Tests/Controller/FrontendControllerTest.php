<?
namespace Volar\FrontendBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FronendControllerTest extends WebTestCase
{
	private $langs=Array("es","en","ca");

	public function testIndex()
	{
		$client = static::createClient();

		foreach ($this->langs as $lang)
		{
			$crawler = $client->request("GET", "/$lang");

			switch ( $lang)
			{
				case "es":
					$search1="ESP | ";
					$search2="Inicio";
				break;
				case "en":
					$search1="ENG";
					$search2="Home";
				break;
				case "ca":
					$search1="CAT | ";
					$search2="Inici";
				break;
			}

			$this->assertCount(1,$crawler->filter("li:contains('$search1')"));
			#$this->assertCount(1,$crawler->filter("li.activo:contains('$search2')"));
		}
	}

#	public function testTeam()
#	{
#		$client = static::createClient();
#
#		foreach ($this->langs as $lang)
#		{
#			$crawler = $client->request("GET", "/$lang/team");
#
#			switch ( $lang)
#			{
#				case "es":
#					$search1="ESP | ";
#					$search2="Inicio";
#					$search3="Compañera incansable de aventuras";
#				break;
#				case "en":
#					$search1="ENG";
#					$search2="Home";
#					$search3="Tireless friend of challenges";
#				break;
#				case "ca":
#					$search1="CAT | ";
#					$search2="Inici";
#					$search3="Companya incansable d'aventures";
#				break;
#			}
#
#			$this->assertCount(1,$crawler->filter("li:contains('$search1')"));
#			$this->assertCount(1,$crawler->filter("li.activo:contains('$search2')"));
#			$this->assertCount(1,$crawler->filter("p:contains('$search3')"));
#		}
#	}
#
#	public function testSchool()
#	{
#		$client = static::createClient();
#
#		foreach ($this->langs as $lang)
#		{
#			$crawler = $client->request("GET", "/$lang/school");
#
#			switch ( $lang)
#			{
#				case "es":
#					$search1="ESP | ";
#					$search2="Inicio";
#					$search3="CURSO DE PERFECCIONAMIENTO";
#				break;
#				case "en":
#					$search1="ENG";
#					$search2="Home";
#					$search3="IMPROVEMENT COURSE";
#				break;
#				case "ca":
#					$search1="CAT | ";
#					$search2="Inici";
#					$search3="CURS D´INICIACIÓ";
#				break;
#			}
#
#			$this->assertCount(1,$crawler->filter("li:contains('$search1')"));
#			$this->assertCount(1,$crawler->filter("li.activo:contains('$search2')"));
#			$this->assertCount(1,$crawler->filter("p.titulo:contains('$search3')"));
#		}
#	}
#
#	public function testTandem()
#	{
#		$client = static::createClient();
#
#		foreach ($this->langs as $lang)
#		{
#			$crawler = $client->request("GET", "/$lang/tandem");
#
#			switch ( $lang)
#			{
#				case "es":
#					$search1="ESP | ";
#					$search2="Inicio";
#					$search3="VUELOS BIPLAZA";
#				break;
#				case "en":
#					$search1="ENG";
#					$search2="Home";
#					$search3="TANDEM PARAGLIDING";
#				break;
#				case "ca":
#					$search1="CAT | ";
#					$search2="Inici";
#					$search3="VOLS BIPLAÇA";
#				break;
#			}
#
#			$this->assertCount(1,$crawler->filter("li:contains('$search1')"));
#			$this->assertCount(1,$crawler->filter("li.activo:contains('$search2')"));
#			$this->assertCount(1,$crawler->filter("p.titulo:contains('$search3')"));
#		}
#	}
#
#	public function testLocation()
#	{
#		$client = static::createClient();
#
#		foreach ($this->langs as $lang)
#		{
#			$crawler = $client->request("GET", "/$lang/location");
#
#			switch ( $lang)
#			{
#				case "es":
#					$search1="ESP | ";
#					$search2="Inicio";
#					$search3="¿DÓNDE VOLAMOS?";
#				break;
#				case "en":
#					$search1="ENG";
#					$search2="Home";
#					$search3="WHERE WE FLY?";
#				break;
#				case "ca":
#					$search1="CAT | ";
#					$search2="Inici";
#					$search3="ON VOLEM?";
#				break;
#			}
#
#			$this->assertCount(1,$crawler->filter("li:contains('$search1')"));
#			$this->assertCount(1,$crawler->filter("li.activo:contains('$search2')"));
#			$this->assertCount(1,$crawler->filter("p.titulo:contains('$search3')"));
#		}
#	}
#
#	public function testWeather()
#	{
#		$client = static::createClient();
#
#		foreach ($this->langs as $lang)
#		{
#			$crawler = $client->request("GET", "/$lang/weather");
#
#			switch ( $lang)
#			{
#				case "es":
#					$search1="ESP | ";
#					$search2="Inicio";
#					$search3="Nota: fuente obtenida";
#				break;
#				case "en":
#					$search1="ENG";
#					$search2="Home";
#					$search3="Note: source from the";
#				break;
#				case "ca":
#					$search1="CAT | ";
#					$search2="Inici";
#					$search3="Nota: font obtinguda del";
#				break;
#			}
#
#			$this->assertCount(1,$crawler->filter("li:contains('$search1')"));
#			$this->assertCount(1,$crawler->filter("li.activo:contains('$search2')"));
#			$this->assertCount(1,$crawler->filter("p:contains('$search3')"));
#		}
#	}
#
#	public function testContact()
#	{
#		$client = static::createClient();
#
#		foreach ($this->langs as $lang)
#		{
#			$crawler = $client->request("GET", "/$lang/contact");
#
#			switch ( $lang)
#			{
#				case "es":
#					$search1="ESP | ";
#					$search2="Inicio";
#					$search3="Si quieres realizar un curso";
#				break;
#				case "en":
#					$search1="ENG";
#					$search2="Home";
#					$search3="If you want to learn to fly";
#				break;
#				case "ca":
#					$search1="CAT | ";
#					$search2="Inici";
#					$search3="Si vols provar l´ experiència";
#				break;
#			}
#
#			$this->assertCount(1,$crawler->filter("li:contains('$search1')"));
#			$this->assertCount(1,$crawler->filter("li.activo:contains('$search2')"));
#			$this->assertCount(1,$crawler->filter("p:contains('$search3')"));
#		}
#	}
}
?>
