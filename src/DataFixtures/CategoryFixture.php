<?php
declare(strict_types=1);

namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        //          Sub - 3
        $dipEtTartinade = array(
            (new Category())->setSlug("tapenade")->setName("tapenade"),
            (new Category())->setSlug("guacamole")->setName("guacamole"),
            (new Category())->setSlug("salsa")->setName("salsa"),
            (new Category())->setSlug("tamara")->setName("tamara"),
            (new Category())->setSlug("tzatziki")->setName("tzatziki"),
            (new Category())->setSlug("hoummous")->setName("hoummous"),
            (new Category())->setSlug("caviar-d-aubergine")->setName("caviar d'aubergine"),
            (new Category())->setSlug("rillettes")->setName("rillettes")
        );

        //          Sub - 3
        $aperitifDinatoireVegan = array(
            (new Category())->setSlug("verinne")->setName("verinne"),
            (new Category())->setSlug("cake-sale-aperitif")->setName("cake salé apéritif "),
            (new Category())->setSlug("dip-et-tartinade")->setName("dip et tartinade"),
            (new Category())->setSlug("aperitif-legume")->setName("apéritif légume"),
            (new Category())->setSlug("aperitif-dinatoire-sans-gluten")->setName("apéritif dînatoire sans gluten"),
            (new Category())->setSlug("aperitif-dinatoire-vegetarien")->setName("apéritif dînatoire végétarien")
        );

        //          Sub - 3
        $aperitifDinatoireSansGluten = array(
            (new Category())->setSlug("verinne")->setName("verinne"),
            (new Category())->setSlug("cake-sale-aperitif")->setName("cake salé apéritif "),
            (new Category())->setSlug("dip-et-tartinade")->setName("dip et tartinade"),
            (new Category())->setSlug("aperitif-legume")->setName("apéritif légume"),
            (new Category())->setSlug("aperitif-dinatoire-vegan")->setName("apéritif dînatoire vegan"),
            (new Category())->setSlug("aperitif-dinatoire-vegetarien")->setName("apéritif dînatoire végétarien")
        );

        //          Sub - 3
        $aperitifDinatoireVegetarien = array(
            (new Category())->setSlug("verinne")->setName("verinne"),
            (new Category())->setSlug("cake-sale-aperitif")->setName("cake salé apéritif "),
            (new Category())->setSlug("dip-et-tartinade")->setName("dip et tartinade"),
            (new Category())->setSlug("aperitif-legume")->setName("apéritif légume"),
            (new Category())->setSlug("aperitif-dinatoire-vegan")->setName("apéritif dînatoire vegan"),
            (new Category())->setSlug("aperitif-dinatoire-sans-gluten")->setName("apéritif dînatoire sans gluten")
        );

        //      Sub - 2
        $aperitifDinatoire = array(
            (new Category())->setSlug("verinne")->setName("verinne"),
            (new Category())->setSlug("cake-sale-aperitif")->setName("cake salé apéritif "),
            (new Category())->setSlug("dip-et-tartinade")->setName("dip et tartinade")
                ->addMultipleChildCategories($dipEtTartinade),
            (new Category())->setSlug("aperitif-legume")->setName("apéritif légume"),
            (new Category())->setSlug("aperitif-dinatoire-vegan")->setName("apéritif dînatoire vegan")
                ->addMultipleChildCategories($aperitifDinatoireVegan),
            (new Category())->setSlug("aperitif-dinatoire-sans-gluten")->setName("apéritif dînatoire sans gluten")
                ->addMultipleChildCategories($aperitifDinatoireSansGluten),
            (new Category())->setSlug("aperitif-dinatoire-vegetarien")->setName("apéritif dînatoire vegetarien")
                ->addMultipleChildCategories($aperitifDinatoireVegetarien),
        );

        //      Sub - 2 
        $boucheeOuAmuseBouche = array(
            (new Category())->setSlug("amuse-bouche-facile-et-rapide")->setName("amuse-bouche facile et rapide"),
            (new Category())->setSlug("amuse-bouche-au-fromage")->setName("amuse-bouche au fromage"),
            (new Category())->setSlug("amuse-bouche-aux-fruits-de-mer")->setName("amuse-bouche aux fruits de mer"),
            (new Category())->setSlug("amuse-bouche-au-saumon")->setName("amuse-bouche au saumon"),
            (new Category())->setSlug("amuse-bouche-au-thon")->setName("amuse-bouche au thon"),
            (new Category())->setSlug("tartelettes-et-mini-tarte")->setName("tartelettes et mini tarte"),
            (new Category())->setSlug("gougeres")->setName("gougères"),
            (new Category())->setSlug("madeleines-salees")->setName("madeleines salées"),
            (new Category())->setSlug("muffin-sale")->setName("muffin salé"),
            (new Category())->setSlug("canapes")->setName("canapés"),
            (new Category())->setSlug("chips")->setName("chips"),
            (new Category())->setSlug("nachos")->setName("nachos"),
            (new Category())->setSlug("gateaux-aperitifs")->setName("gâteaux apéritifs"),
            (new Category())->setSlug("croissants")->setName("croissants"),
            (new Category())->setSlug("blinis")->setName("blinis"),
            (new Category())->setSlug("saucisses-et-knackis")->setName("saucisses et knackis"),
            (new Category())->setSlug("accras")->setName("accras"),
            (new Category())->setSlug("achards")->setName("achards"),
            (new Category())->setSlug("socca")->setName("socca"),
            (new Category())->setSlug("chaussons-empanadas")->setName("chaussons, empanadas"),
            (new Category())->setSlug("beignets-sales")->setName("beignets salés")
        );

        //      Sub - 2
        $cocktailAperitif = array(
            (new Category())->setSlug("cocktail-sans-alcool")->setName("cocktail sans alcool"),
            (new Category())->setSlug("mojito")->setName("mojito"),
            (new Category())->setSlug("margarita")->setName("margarita"),
            (new Category())->setSlug("punch")->setName("punch"),
            (new Category())->setSlug("sangria")->setName("sangria"),
            (new Category())->setSlug("cocktail-champagne")->setName("cocktail champagne"),
            (new Category())->setSlug("cocktail-rhum")->setName("cocktail rhum"),
            (new Category())->setSlug("cocktail-vodka")->setName("cocktail vodka"),
            (new Category())->setSlug("cocktail-gin")->setName("cocktail gin"),
            (new Category())->setSlug("cocktail-whisky")->setName("cocktail whisky"),
            (new Category())->setSlug("martini")->setName("martini"),
            (new Category())->setSlug("pina-colada")->setName("piña colada"),
            (new Category())->setSlug("tequila-sunrise")->setName("tequila sunrise"),
            (new Category())->setSlug("caipirinha")->setName("caipirinha"),
            (new Category())->setSlug("spritz")->setName("spritz"),
            (new Category())->setSlug("bloody-mary")->setName("bloody mary")
        );

        //  Sub - 1
        $aperitifs = array(
            (new Category())->setSlug("aperitif-dinatoire")->setName("apéritif dînatoire")
                ->addMultipleChildCategories($aperitifDinatoire),
            (new Category())->setSlug("apero-leger")->setName("apéro léger"),
            (new Category())->setSlug("bouchee-ou-amuse-bouche")->setName("bouchée ou amuse-bouche")
                ->addMultipleChildCategories($boucheeOuAmuseBouche),
            (new Category())->setSlug("cocktail-aperitif")->setName("cocktail apéritif")
                ->addMultipleChildCategories($boucheeOuAmuseBouche),
            (new Category())->setSlug("apero-pas-cher")->setName("cocktail apéritif")
                ->addMultipleChildCategories($cocktailAperitif),
            (new Category())->setSlug("aperitif-de-noël")->setName("apéritif de noël")
        );

        //      Sub - 2
        $entreeFroide = array(
            (new Category())->setSlug("terrine-pate")->setName("terrine, pâté"),
            (new Category())->setSlug("foie-gras")->setName("foie gras"),
            (new Category())->setSlug("carpaccio")->setName("carpaccio"),
            (new Category())->setSlug("cake-sale")->setName("cake salé"),
            (new Category())->setSlug("poisson-cru")->setName("poisson cru"),
            (new Category())->setSlug("tartare")->setName("tartare"),
            (new Category())->setSlug("flan-sale")->setName("flan salé"),
            (new Category())->setSlug("gaspacho-soupe-froide")->setName("gaspacho, soupe froide"),
            (new Category())->setSlug("saucisson")->setName("saucisson"),
            (new Category())->setSlug("tomate-mozzarella")->setName("tomate mozzarella"),
            (new Category())->setSlug("carottes-rapees")->setName("carottes râpées"),
            (new Category())->setSlug("autres-crudites")->setName("autres crudités")

        );

        //      Sub - 2
        $entreeChaude = array(
            (new Category())->setSlug("soupe")->setName("soupe"),
            (new Category())->setSlug("oeuf-cocotte")->setName("œuf cocotte"),
            (new Category())->setSlug("mini-tourte")->setName("mini tourte"),
            (new Category())->setSlug("souffle")->setName("soufflé"),
            (new Category())->setSlug("tartine")->setName("tartine"),
            (new Category())->setSlug("oeufs-cuits")->setName("oeufs cuits")
        );

        //      Sub - 2
        $boucheesEtRaviolis = array(
            (new Category())->setSlug("raviolis")->setName("raviolis"),
            (new Category())->setSlug("ravioles")->setName("ravioles"),
            (new Category())->setSlug("dim-sum")->setName("dim sum"),
            (new Category())->setSlug("gyoza")->setName("gyoza"),
            (new Category())->setSlug("nems")->setName("nems"),
            (new Category())->setSlug("rouleaux-de-printemps")->setName("rouleaux de printemps")
        );

        //      Sub - 2
        $feuilleteBrick = array(
            (new Category())->setSlug("feuillete")->setName("feuilleté"),
            (new Category())->setSlug("brick")->setName("brick"),
            (new Category())->setSlug("aumoniere")->setName("aumônière")
        );

        //  Sub - 1
        $entrees = array(
            (new Category())->setSlug("entree-froide")->setName("entrée froide")
                ->addMultipleChildCategories($entreeFroide),
            (new Category())->setSlug("entree-chaude")->setName("entrée chaude")
                ->addMultipleChildCategories($entreeChaude),
            (new Category())->setSlug("bouchees-et-raviolis")->setName("bouchées et raviolis")
                ->addMultipleChildCategories($boucheesEtRaviolis),
            (new Category())->setSlug("feuillete-brick")->setName("feuilleté, brick")
                ->addMultipleChildCategories($feuilleteBrick),
            (new Category())->setSlug("entree-facile")->setName("entrée facile"),
            (new Category())->setSlug("entree-rapide")->setName("entrée rapide"),
            (new Category())->setSlug("entree-legere")->setName("entrée légère"),
            (new Category())->setSlug("entree-de-noel")->setName("entrée de noël")
        );

        //          Sub - 3
        $viandeEnSauce = array(
            (new Category())->setSlug("daube-bourguignon")->setName("daube, bourguignon"),
            (new Category())->setSlug("coq-au-vin")->setName("coq au vin"),
            (new Category())->setSlug("osso-bucco")->setName("osso bucco"),
            (new Category())->setSlug("blanquette")->setName("blanquette"),
            (new Category())->setSlug("paupiette")->setName("paupiette"),
            (new Category())->setSlug("veau-marengo")->setName("veau marengo"),
            (new Category())->setSlug("curry-de-poulet")->setName("curry de poulet"),
            (new Category())->setSlug("poulet-basquaise")->setName("poulet basquaise"),
            (new Category())->setSlug("poulet-en-sauce")->setName("poulet en sauce"),
            (new Category())->setSlug("blanc-de-poulet")->setName("blanc de poulet"),
            (new Category())->setSlug("lapin-en-sauce")->setName("lapin en sauce"),
            (new Category())->setSlug("saute-de-veau")->setName("sauté de veau"),
            (new Category())->setSlug("saute-de-porc")->setName("sauté de porc"),
            (new Category())->setSlug("cailles")->setName("cailles"),
            (new Category())->setSlug("viande-a-l-indienne")->setName("viande à l'indienne"),
            (new Category())->setSlug("cuisses-de-volaille")->setName("cuisses de volaille"),
            (new Category())->setSlug("pintade-en-sauce")->setName("pintade en sauce"),
            (new Category())->setSlug("saute-de-volaille")->setName("sauté de volaille"),
            (new Category())->setSlug("poule-au-pot")->setName("poule au pot"),
            (new Category())->setSlug("navarin-d-agneau")->setName("navarin d'agneau"),
            (new Category())->setSlug("joue-de-porc")->setName("joue de porc"),
            (new Category())->setSlug("cuisses-de-grenouilles")->setName("cuisses de grenouilles"),
            (new Category())->setSlug("boudin")->setName("boudin"),
            (new Category())->setSlug("joue-de-boeuf")->setName("joue de boeuf")
        );

        //          Sub - 3
        $viandeRoti = array(
            (new Category())->setSlug("gigot-d-agneau")->setName("gigot d'agneau"),
            (new Category())->setSlug("kebab")->setName("kebab"),
            (new Category())->setSlug("cotelettes")->setName("côtelettes"),
            (new Category())->setSlug("roti-de-boeuf")->setName("rôti de bœuf"),
            (new Category())->setSlug("roti-de-veau")->setName("rôti de veau"),
            (new Category())->setSlug("roti-de-porc")->setName("rôti de porc"),
            (new Category())->setSlug("poulet-roti")->setName("poulet rôti"),
            (new Category())->setSlug("travers-de-porc")->setName("travers de porc"),
            (new Category())->setSlug("filet-mignon")->setName("filet mignon"),
            (new Category())->setSlug("canard-a-l-orange")->setName("canard à l'orange"),
            (new Category())->setSlug("magret-de-canard")->setName("magret de canard"),
            (new Category())->setSlug("poulet-marine")->setName("poulet mariné"),
            (new Category())->setSlug("lapin")->setName("lapin"),
            (new Category())->setSlug("confit-de-canard")->setName("confit de canard"),
            (new Category())->setSlug("recettes-de-viande-legeres")->setName("recettes de viande légères"),
            (new Category())->setSlug("saucisses")->setName("saucisses"),
            (new Category())->setSlug("escalopes")->setName("escalopes"),
            (new Category())->setSlug("hamburger")->setName("hamburger"),
            (new Category())->setSlug("pintade")->setName("pintade"),
            (new Category())->setSlug("volaille-de-noel")->setName("petit-sale"),
            (new Category())->setSlug("gibier")->setName("gibier"),
            (new Category())->setSlug("nuggets-de-poulet")->setName("nuggets de poulet"),
            (new Category())->setSlug("hot-dog")->setName("hot-dog"),
            (new Category())->setSlug("piece-de-boeuf")->setName("pièce de boeuf")
        );

        //          Sub - 3
        $boulettesDeViande = array(
            (new Category())->setSlug("kefta")->setName("kefta"),
            (new Category())->setSlug("falafel")->setName("falafel")
        );

        //          Sub - 3
        $abats = array(
            (new Category())->setSlug("rognons")->setName("rognons"),
            (new Category())->setSlug("ris-de-veau")->setName("ris de veau"),
            (new Category())->setSlug("cervelle")->setName("cervelle"),
            (new Category())->setSlug("langue")->setName("langue"),
            (new Category())->setSlug("tete-de-veau")->setName("tête de veau"),
            (new Category())->setSlug("tripes")->setName("tripes"),
            (new Category())->setSlug("foie")->setName("foie")
        );

        //      Sub - 2
        $viande = array(
            (new Category())->setSlug("viande-en-sauce")->setName("viande en sauce")
                ->addMultipleChildCategories($viandeEnSauce),
            (new Category())->setSlug("viande-rotie")->setName("viande rôtie")
                ->addMultipleChildCategories($viandeRoti),
            (new Category())->setSlug("boulettes-de-viande")->setName("boulettes de viande")
                ->addMultipleChildCategories($boulettesDeViande),
            (new Category())->setSlug("abats")->setName("abats")
                ->addMultipleChildCategories($abats),
            (new Category())->setSlug("andouilette")->setName("andouilette")
        );

        //      Sub - 2
        $poisson = array(
            (new Category())->setSlug("poisson-au-four")->setName("poisson au four"),
            (new Category())->setSlug("poisson-pane")->setName("poisson pané"),
            (new Category())->setSlug("poisson-en-papillote")->setName("poisson en papillote"),
            (new Category())->setSlug("poisson-facile")->setName("poisson facile"),
            (new Category())->setSlug("filets-de-poisson")->setName("filets de poisson"),
            (new Category())->setSlug("soupe-de-poisson")->setName("soupe de poisson"),
            (new Category())->setSlug("saumon-en-sauce")->setName("saumon en sauce"),
            (new Category())->setSlug("poisson-en-croute")->setName("poisson en croûte"),
            (new Category())->setSlug("poisson-en-sauce")->setName("poisson en sauce"),
            (new Category())->setSlug("fish-and-chips")->setName("fish and chips")
        );

        //      Sub - 2
        $fruitsDeMer = array(
            (new Category())->setSlug("crevettes")->setName("crevettes"),
            (new Category())->setSlug("saint-jacques")->setName("saint-jacques"),
            (new Category())->setSlug("crustaces")->setName("crustacés"),
            (new Category())->setSlug("calamar-poulpe")->setName("calamar, poulpe"),
            (new Category())->setSlug("moules")->setName("moules"),
            (new Category())->setSlug("huitres")->setName("huîtres"),
            (new Category())->setSlug("coquillages")->setName("coquillages")
        );

        //      Sub - 2
        $platUnique = array(
            (new Category())->setSlug("pot-au-feu")->setName("pot-au-feu"),
            (new Category())->setSlug("choucroute")->setName("choucroute"),
            (new Category())->setSlug("couscous")->setName("couscous"),
            (new Category())->setSlug("tajine")->setName("tajine"),
            (new Category())->setSlug("goulash")->setName("goulash"),
            (new Category())->setSlug("hachis-parmentier")->setName("hachis parmentier"),
            (new Category())->setSlug("paella")->setName("paella"),
            (new Category())->setSlug("chili-con-carne-et-sin-carne")->setName("chili con carne et sin carne"),
            (new Category())->setSlug("cassoulet")->setName("cassoulet"),
            (new Category())->setSlug("fondue-a-la-viande")->setName("fondue à la viande"),
            (new Category())->setSlug("galette-de-sarrasin-crepe-salee")->setName("galette de sarrasin, crêpe salée"),
            (new Category())->setSlug("moussaka")->setName("moussaka"),
            (new Category())->setSlug("carbonade")->setName("carbonade"),
            (new Category())->setSlug("baeckeoffe")->setName("baeckeoffe"),
            (new Category())->setSlug("rougail")->setName("rougail"),
            (new Category())->setSlug("endives-au-jambon")->setName("endives au jambon"),
            (new Category())->setSlug("saucisse-lentilles")->setName("saucisse lentilles"),
            (new Category())->setSlug("croque-monsieur")->setName("croque-monsieur"),
            (new Category())->setSlug("plats-mexicains")->setName("plats mexicains"),
            (new Category())->setSlug("pastilla")->setName("pastilla"),
            (new Category())->setSlug("legumes-farcis")->setName("légumes farcis"),
            (new Category())->setSlug("cordon-bleu")->setName("cordon bleu"),
            (new Category())->setSlug("quenelles")->setName("quenelles"),
            (new Category())->setSlug("plats-africains")->setName("plats africains"),
            (new Category())->setSlug("plats-thai")->setName("plats thaï"),
            (new Category())->setSlug("plats-vietnamiens")->setName("plats vietnamiens"),
            (new Category())->setSlug("plats-chinois")->setName("plats chinois"),
            (new Category())->setSlug("plats-japonais")->setName("plats japonais"),
            (new Category())->setSlug("plats-indiens")->setName("plats indiens"),
            (new Category())->setSlug("plats-coreens")->setName("plats coréens"),
            (new Category())->setSlug("plats-indonesiens")->setName("plats indonésiens"),
            (new Category())->setSlug("piperade")->setName("piperade"),
            (new Category())->setSlug("gaufre-salee")->setName("gaufre salée"),
            (new Category())->setSlug("plats-philippins")->setName("plats philippins"),
            (new Category())->setSlug("plats-colombiens")->setName("plats colombiens"),
            (new Category())->setSlug("galette-de-pomme-de-terre")->setName("galette de pomme de terre"),
            (new Category())->setSlug("jardiniere-ou-poelee")->setName("jardinière ou poêlée")
        );

        //      Sub - 2
        $oeufs = array(
            (new Category())->setSlug("omelette")->setName("omelette"),
            (new Category())->setSlug("oeufs-brouilles")->setName("œufs brouillés"),
            (new Category())->setSlug("oeuf-poche")->setName("œuf poché")  
        );

        //      Sub - 2
        $platVegetarien = array(
            (new Category())->setSlug("lasagne-vegetarienne")->setName("lasagne végétarienne"),
            (new Category())->setSlug("curry-de-legumes")->setName("curry de légumes"),
            (new Category())->setSlug("galette-de-legumes")->setName("galette de légumes"),
            (new Category())->setSlug("galette-de-cereales")->setName("galette de céréales"),
            (new Category())->setSlug("hamburger-vegetarien")->setName("hamburger végétarien"),
            (new Category())->setSlug("dal")->setName("dal"),
            (new Category())->setSlug("jardiniere-ou-poelee-de-legumes")->setName("jardinière ou poêlée de légumes"),
            (new Category())->setSlug("aubergine")->setName("aubergine"),
            (new Category())->setSlug("tofu")->setName("tofu")
        );

        //          Sub - 3
        $patesEnSauce = array(
            (new Category())->setSlug("carbonara")->setName("carbonara"),
            (new Category())->setSlug("bolognaise")->setName("bolognaise")
        );

        //      Sub - 2
        $patesRizSemoule = array(
            (new Category())->setSlug("pates-en-sauce")->setName("pâtes en sauce")
                ->addMultipleChildCategories($patesEnSauce),
            (new Category())->setSlug("gratin-de-pates")->setName("gratin de pâtes"),
            (new Category())->setSlug("plat-de-riz")->setName("plat de riz"),
            (new Category())->setSlug("spaghetti")->setName("spaghetti"),
            (new Category())->setSlug("lasagne")->setName("lasagne"),
            (new Category())->setSlug("tagliatelle")->setName("tagliatelle"),
            (new Category())->setSlug("risotto")->setName("risotto"),
            (new Category())->setSlug("polenta")->setName("polenta"),
            (new Category())->setSlug("macaronis")->setName("macaronis"),
            (new Category())->setSlug("autres-pates")->setName("autres pâtes"),
            (new Category())->setSlug("gnocchis")->setName("gnocchis"),
            (new Category())->setSlug("quinoa")->setName("quinoa")
        );

        //      Sub - 2
        $platAuFromage = array(
            (new Category())->setSlug("fondue-au-fromage")->setName("fondue au fromage"),
            (new Category())->setSlug("raclette")->setName("raclette"),
            (new Category())->setSlug("tartiflette")->setName("tartiflette"),
            (new Category())->setSlug("camembert")->setName("camembert"),
            (new Category())->setSlug("mont-d-or")->setName("mont d'or"),
            (new Category())->setSlug("truffade")->setName("truffade"),
            (new Category())->setSlug("poutine")->setName("poutine")
        );

        //  Sub - 1
        $platPrincipal = array(
            (new Category())->setSlug("viande")->setName("viande")
                ->addMultipleChildCategories($viande),
            (new Category())->setSlug("poisson")->setName("poisson")
                ->addMultipleChildCategories($poisson),
            (new Category())->setSlug("fruits-de-mer")->setName("fruits de mer")
                ->addMultipleChildCategories($fruitsDeMer),
            (new Category())->setSlug("plat-unique")->setName("plat unique")
                ->addMultipleChildCategories($platUnique),
            (new Category())->setSlug("oeufs")->setName("œufs")
                ->addMultipleChildCategories($oeufs),
            (new Category())->setSlug("plat-vegetarien")->setName("plat végétarien")
                ->addMultipleChildCategories($platVegetarien),
            (new Category())->setSlug("pates-riz-semoule")->setName("pâtes, riz, semoule")
                ->addMultipleChildCategories($patesRizSemoule),
            (new Category())->setSlug("plats-au-fromage")->setName("plats au fromage")
                ->addMultipleChildCategories($platAuFromage)
        );
        
        //      Sub - 2
        $dessertAuChocolat = array(
            (new Category())->setSlug("mousse-au-chocolat")->setName("mousse au chocolat"),
            (new Category())->setSlug("creme-au-chocolat")->setName("crème au chocolat"),
            (new Category())->setSlug("glace-au-chocolat")->setName("glace au chocolat"),
            (new Category())->setSlug("profiteroles")->setName("profiteroles"),
            (new Category())->setSlug("Tarte-au-chocolat")->setName("Tarte au chocolat"),
            (new Category())->setSlug("gateau-au-chocolat")->setName("gâteau au chocolat"),
        );

        //          Sub - 3
        $petitsGateau = array(
            (new Category())->setSlug("meringues")->setName("meringues"),
            (new Category())->setSlug("sables")->setName("sablés"),
            (new Category())->setSlug("macarons")->setName("macarons"),
            (new Category())->setSlug("muffin")->setName("muffin"),
            (new Category())->setSlug("madeleine")->setName("madeleine"),
            (new Category())->setSlug("financiers")->setName("financiers"),
            (new Category())->setSlug("tuiles")->setName("tuiles"),
            (new Category())->setSlug("cupcake")->setName("cupcake"),
            (new Category())->setSlug("cannelés")->setName("cannelés"),
            (new Category())->setSlug("cookie")->setName("cookie"),
            (new Category())->setSlug("palets-et-langues-de-chat")->setName("palets et langues de chat"),
            (new Category())->setSlug("biscuits-de-noel")->setName("biscuits de noël"),
            (new Category())->setSlug("autres-biscuits-et-petits-gateaux")->setName("autres biscuits et petits gâteaux"),
            (new Category())->setSlug("pop-cakes")->setName("pop cakes"),
            (new Category())->setSlug("speculoos")->setName("speculoos"),
            (new Category())->setSlug("bredele")->setName("bredele"),
            (new Category())->setSlug("makrout")->setName("makrout"),
            (new Category())->setSlug("mantecaos")->setName("mantecaos"),
            (new Category())->setSlug("baklava")->setName("baklava"),
            (new Category())->setSlug("maamouls")->setName("maamouls")
        );

        //          Sub - 3
        $gateauAuChocolat = array(
            (new Category())->setSlug("fondant-au-chocolat")->setName("fondant au chocolat"),
            (new Category())->setSlug("coulant-au-chocolat")->setName("coulant au chocolat"),
            (new Category())->setSlug("cake-au-chocolat")->setName("cake au chocolat"),
            (new Category())->setSlug("mi-cuit-au-chocolat")->setName("mi-cuit au chocolat"),
            (new Category())->setSlug("marquise-au-chocolat")->setName("marquise au chocolat"),
            (new Category())->setSlug("trianon")->setName("trianon"),
            (new Category())->setSlug("foret-noire")->setName("forêt noire"),
            (new Category())->setSlug("reine-de-saba")->setName("reine de saba"),
            (new Category())->setSlug("crumble-poire-chocolat")->setName("crumble poire chocolat"),
            (new Category())->setSlug("brownie")->setName("brownie"),
            (new Category())->setSlug("moka")->setName("moka"),
            (new Category())->setSlug("moelleux-au-chocolat")->setName("moelleux au chocolat")
        );

        //          Sub - 3
        $cakes = array(
            (new Category())->setSlug("cake-au-citron")->setName("cake au citron"),
            (new Category())->setSlug("cake-aux-fruits-confits")->setName("cake aux fruits confits"),
            (new Category())->setSlug("autres-cakes")->setName("autres cakes")
        );

        //      Sub - 2
        $gateau = array(
            (new Category())->setSlug("petits-gateaux")->setName("petits gâteaux")
                ->addMultipleChildCategories($petitsGateau),
            (new Category())->setSlug("gateau-au-chocolat")->setName("gâteau au chocolat")
                ->addMultipleChildCategories($gateauAuChocolat),
            (new Category())->setSlug("gateau-d-anniversaire")->setName("gâteau d'anniversaire"),
            (new Category())->setSlug("gateau-a-l-ananas")->setName("gâteau à l'ananas"),
            (new Category())->setSlug("gateau-a-la-banane")->setName("gâteau à la banane"),
            (new Category())->setSlug("gateau-au-yaourt")->setName("gâteau au yaourt"),
            (new Category())->setSlug("gateau-aux-carottes")->setName("gâteau aux carottes"),
            (new Category())->setSlug("gateau-aux-noix")->setName("gâteau aux noix"),
            (new Category())->setSlug("creusois")->setName("creusois"),
            (new Category())->setSlug("gateau-aux-pommes")->setName("gâteau aux pommes"),
            (new Category())->setSlug("gateau-breton")->setName("gâteau breton"),
            (new Category())->setSlug("gateau-de-semoule")->setName("gâteau de semoule"),
            (new Category())->setSlug("gateau-magique")->setName("gâteau magique"),
            (new Category())->setSlug("gateau-marbre")->setName("gâteau marbré"),
            (new Category())->setSlug("fraisier")->setName("fraisier"),
            (new Category())->setSlug("framboisier")->setName("framboisier"),
            (new Category())->setSlug("quatre-quarts")->setName("quatre quarts"),
            (new Category())->setSlug("mugcake")->setName("mugcake"),
            (new Category())->setSlug("kouign-amann")->setName("kouign amann"),
            (new Category())->setSlug("gateaux-aux-fruits")->setName("gâteaux aux fruits"),
            (new Category())->setSlug("biscuit-de-savoie")->setName("biscuit de savoie"),
            (new Category())->setSlug("genoise")->setName("génoise"),
            (new Category())->setSlug("gateau-roule")->setName("gâteau roulé"),
            (new Category())->setSlug("cakes")->setName("cakes")
                ->addMultipleChildCategories($cakes),
            (new Category())->setSlug("autres-gateaux")->setName("autres gâteaux"),
            (new Category())->setSlug("gateau-de-riz")->setName("gâteau de riz"),
            (new Category())->setSlug("tourteau")->setName("tourteau")
        );

        //      Sub - 2
        $flan = array(
            (new Category())->setSlug("flan-coco")->setName("flan coco"),
            (new Category())->setSlug("far-breton")->setName("far breton"),
            (new Category())->setSlug("clafoutis")->setName("clafoutis"),
            (new Category())->setSlug("pasteis-de-nata")->setName("pasteis de nata"),
            (new Category())->setSlug("flognarde")->setName("flognarde")
        );

        //      Sub - 2
        $creme = array(
            (new Category())->setSlug("creme-dessert")->setName("crème dessert"),
            (new Category())->setSlug("riz-au-lait")->setName("riz au lait"),
            (new Category())->setSlug("creme-patissiere")->setName("crème pâtissière"),
            (new Category())->setSlug("creme-caramel")->setName("crème caramel"),
            (new Category())->setSlug("creme-anglaise")->setName("crème anglaise"),
            (new Category())->setSlug("creme-vanille")->setName("crème vanille"),
            (new Category())->setSlug("creme-chantilly")->setName("crème chantilly"),
            (new Category())->setSlug("panna-cotta")->setName("panna cotta"),
            (new Category())->setSlug("creme-brulee")->setName("crème brûlée"),
            (new Category())->setSlug("oeufs-au-lait")->setName("oeufs au lait"),
            (new Category())->setSlug("liegeois")->setName("liégeois"),
            (new Category())->setSlug("creme-renversee")->setName("crème renversée")
        );

        //      Sub - 2
        $mousse = array(
            (new Category())->setSlug("mousse-au-chocolat")->setName("mousse au chocolat"),
            (new Category())->setSlug("mousse-fruits")->setName("mousse fruits"),
            (new Category())->setSlug("mousse-citron")->setName("mousse citron")
        );

        //      Sub - 2
        $crumble = array(
            (new Category())->setSlug("crumble-aux-pommes")->setName("crumble aux pommes"),
            (new Category())->setSlug("crumble-fruits-rouges")->setName("crumble fruits rouges"),
            (new Category())->setSlug("crumble-banane")->setName("crumble banane"),
            (new Category())->setSlug("crumble-poire-chocolat")->setName("crumble poire chocolat"),
            (new Category())->setSlug("autres crumbles")->setName("autres crumbles")
        );

        //      Sub - 2
        $beignetEtFriture = array(
            (new Category())->setSlug("beignets-sucres")->setName("beignets sucrés"),
            (new Category())->setSlug("churros")->setName("churros"),
            (new Category())->setSlug("donuts")->setName("donuts")
        );

        //      Sub - 2
        $tartes = array(
            (new Category())->setSlug("tarte-aux-pommes")->setName("tarte aux pommes"),
            (new Category())->setSlug("tarte-aux-fraises")->setName("tarte aux fraises"),
            (new Category())->setSlug("tarte-au-citron")->setName("tarte au citron"),
            (new Category())->setSlug("tarte-poire-chocolat")->setName("tarte poire chocolat"),
            (new Category())->setSlug("tarte-au-chocolat")->setName("tarte au chocolat"),
            (new Category())->setSlug("tarte tatin")->setName("tarte tatin"),
            (new Category())->setSlug("tate-normande")->setName("tate normande"),
            (new Category())->setSlug("tarte-a-la-rhubarbe")->setName("tarte à la rhubarbe"),
            (new Category())->setSlug("tarte-a-la-banane")->setName("tarte à la banane"),
            (new Category())->setSlug("tarte-au-fromage-blanc")->setName("tarte au fromage blanc"),
            (new Category())->setSlug("tarte-au-sucre")->setName("tarte au sucre"),
            (new Category())->setSlug("tarte-aux-abricots")->setName("tarte aux abricots"),
            (new Category())->setSlug("tarte-aux-framboises")->setName("tarte aux framboises"),
            (new Category())->setSlug("tarte-aux-mirabelles")->setName("tarte aux mirabelles"),
            (new Category())->setSlug("tarte-aux-myrtilles")->setName("tarte aux myrtilles"),
            (new Category())->setSlug("tarte-aux-noix")->setName("tarte aux noix"),
            (new Category())->setSlug("tarte-aux-peches")->setName("tarte aux pêches"),
            (new Category())->setSlug("tarte-aux-poires")->setName("tarte aux poires"),
            (new Category())->setSlug("tarte-aux-prunes")->setName("tarte aux prunes"),
            (new Category())->setSlug("pate-a-tarte")->setName("pâte à tarte"),
            (new Category())->setSlug("tarte-aux-fruits-rouges")->setName("tarte aux fruits rouges"),
            (new Category())->setSlug("tarte-aux-pruneaux")->setName("tarte aux pruneaux"),
            (new Category())->setSlug("tarte-aux-groseilles")->setName("tarte aux groseilles"),
            (new Category())->setSlug("tarte-noix-de-coco")->setName("tarte noix de coco"),
            (new Category())->setSlug("tarte-tropezienne")->setName("tarte tropézienne")
        );

        //      Sub - 2
        $sauceSucree = array(
            (new Category())->setSlug("sabayon")->setName("sabayon"),
            (new Category())->setSlug("ganache")->setName("ganache"),
            (new Category())->setSlug("sauce-chocolat")->setName("sauce chocolat"),
            (new Category())->setSlug("coulis-de-fruits-rouges")->setName("coulis de fruits rouges"),
            (new Category())->setSlug("coulis-au-chocolat")->setName("coulis au chocolat"),
            (new Category())->setSlug("pate-a-tartiner")->setName("pâte à tartiner"),
            (new Category())->setSlug("caramel")->setName("caramel"),
            (new Category())->setSlug("curd")->setName("curd"),
            (new Category())->setSlug("autres-curds")->setName("autres curds"),
            (new Category())->setSlug("glacage")->setName("glaçage"),
            (new Category())->setSlug("creme-de-marrons")->setName("crème de marrons")
        );

        //      Sub - 2
        $gateauGlace = array(
            (new Category())->setSlug("vacherin")->setName("vacherin"),
            (new Category())->setSlug("pavlova")->setName("pavlova"),
            (new Category())->setSlug("parfait-glace")->setName("parfait glacé")
        );

        //      Sub - 2
        $charlotte = array(
            (new Category())->setSlug("charlotte-aux-fraises")->setName("charlotte aux fraises"),
            (new Category())->setSlug("charlotte-au-chocolat")->setName("charlotte au chocolat"),
            (new Category())->setSlug("charlotte-aux-poires")->setName("charlotte aux poires"),
            (new Category())->setSlug("charlotte-aux-fruits")->setName("charlotte aux fruits")
        );

        //      Sub - 2
        $choux = array(
            (new Category())->setSlug("piece-montee")->setName("pièce montée"),
            (new Category())->setSlug("choux-a-la-creme")->setName("choux à la crème"),
            (new Category())->setSlug("chouquettes")->setName("chouquettes"),
            (new Category())->setSlug("eclair")->setName("éclair"),
            (new Category())->setSlug("religieuse")->setName("religieuse"),
            (new Category())->setSlug("pate-a-choux")->setName("pâte à choux"),
            (new Category())->setSlug("paris-brest")->setName("paris-brest"),
            (new Category())->setSlug("autres-choux")->setName("autres choux")
        );

        //      Sub - 2
        $bucheDeNoel = array(
            (new Category())->setSlug("buche-au-chocolat")->setName("bûche au chocolat"),
            (new Category())->setSlug("buche-glacee")->setName("bûche glacée"),
            (new Category())->setSlug("buche-tiramisu")->setName("bûche tiramisu"),
            (new Category())->setSlug("buche-framboise")->setName("bûche framboise"),
            (new Category())->setSlug("buche-vanille")->setName("bûche vanille"),
            (new Category())->setSlug("autres-buches")->setName("autres bûches")
        );

        //      Sub - 2
        $saladeDeFruits = array(
            (new Category())->setSlug("salade-de-fruits-d-hiver")->setName("salade de fruits d'hiver"),
            (new Category())->setSlug("salade-de-fruits-exotiques")->setName("salade de fruits exotiques"),
            (new Category())->setSlug("salade-de-fruits-de-noel")->setName("salade de fruits de noël"),
            (new Category())->setSlug("salade-de-fruits-originale")->setName("salade de fruits originale")
        );

        //      Sub - 2
        $compote = array(
            (new Category())->setSlug("compote-de-pomme")->setName("compote de pomme"),
            (new Category())->setSlug("compote-de-poire")->setName("compote de poire"),
            (new Category())->setSlug("compote-de-banane")->setName("compote de banane")
        );

        //      Sub - 2
        $viennoiseries = array(
            (new Category())->setSlug("croissant")->setName("croissant"),
            (new Category())->setSlug("pain-au-chocolat")->setName("pain au chocolat"),
            (new Category())->setSlug("pain-aux-raisins")->setName("pain aux raisins"),
            (new Category())->setSlug("chausson-aux-pommes")->setName("chausson aux pommes")
        );

        //      Sub - 2
        $brioche = array(
            (new Category())->setSlug("brioche-vendeenne")->setName("brioche vendéenne"),
            (new Category())->setSlug("mouna")->setName("mouna"),
            (new Category())->setSlug("brioche-de-paques")->setName("brioche de pâques"),
            (new Category())->setSlug("brioche-suisse")->setName("brioche suisse"),
            (new Category())->setSlug("autres-brioches")->setName("autres-brioches")
        );

        //          Sub - 3
        $coupeGlacee = array(
            (new Category())->setSlug("bananaSplit")->setName("banana split"),
            (new Category())->setSlug("poire-belle-helene")->setName("poire belle-hélène")
        );

        //      Sub - 2
        $dessertGlace = array(
            (new Category())->setSlug("sorbet")->setName("sorbet"),
            (new Category())->setSlug("glace")->setName("glace"),
            (new Category())->setSlug("coupe-glacee")->setName("coupe glacée")
                ->addMultipleChildCategories($coupeGlacee),
            (new Category())->setSlug("granite")->setName("granité")
        );

        //      Sub - 2
        $crepes = array(
            (new Category())->setSlug("pate-a-crepes")->setName("pâte à crêpes"),
            (new Category())->setSlug("crepe-suzette")->setName("crêpe suzette"),
            (new Category())->setSlug("crepe-sans-oeufs")->setName("crêpe sans œufs"),
            (new Category())->setSlug("pancake")->setName("pancake"),
            (new Category())->setSlug("crepes-sucrees")->setName("crêpes sucrées")
        );
        
        //      Sub - 2
        $gateauxALaFrangipane = array(
            (new Category())->setSlug("pithiviers")->setName("pithiviers"),
            (new Category())->setSlug("conversation")->setName("conversation"),
            (new Category())->setSlug("tarte-a-la-frangipane")->setName("tarte à la frangipane")
        );

        //      Sub - 2
        $petitDejeuner = array(
            (new Category())->setSlug("porridge")->setName("porridge"),
            (new Category())->setSlug("muesli")->setName("muesli"),
            (new Category())->setSlug("granola")->setName("granola")
        );

        //  Sub - 1
        $desserts = array(
            (new Category())->setSlug("dessert-au-chocolat")->setName("dessert au chocolat")
                ->addMultipleChildCategories($dessertAuChocolat),
            (new Category())->setSlug("gateau")->setName("gâteau")
                ->addMultipleChildCategories($gateau),
            (new Category())->setSlug("flan")->setName("flan")
                ->addMultipleChildCategories($flan),
            (new Category())->setSlug("creme")->setName("crème")
                ->addMultipleChildCategories($creme),
            (new Category())->setSlug("mousse")->setName("mousse")
                ->addMultipleChildCategories($mousse),
            (new Category())->setSlug("crumble")->setName("crumble")
                ->addMultipleChildCategories($crumble),
            (new Category())->setSlug("beignet-et-friture")->setName("beignet et friture")
                ->addMultipleChildCategories($beignetEtFriture),
            (new Category())->setSlug("tarte")->setName("tarte")
                ->addMultipleChildCategories($tartes),
            (new Category())->setSlug("sauce-sucree")->setName("sauce sucrée")
                ->addMultipleChildCategories($sauceSucree),
            (new Category())->setSlug("gateau-glace")->setName("gâteau glacé")
                ->addMultipleChildCategories($gateauGlace),
            (new Category())->setSlug("charlotte")->setName("charlotte")
                ->addMultipleChildCategories($charlotte),
            (new Category())->setSlug("savarin")->setName("savarin"),
            (new Category())->setSlug("bavarois")->setName("bavarois"),
            (new Category())->setSlug("choux")->setName("choux")
                ->addMultipleChildCategories($choux),
            (new Category())->setSlug("strudel")->setName("strudel"),
            (new Category())->setSlug("kougelhopf")->setName("kougelhopf"),
            (new Category())->setSlug("galette-des-rois")->setName("galette des rois"),
            (new Category())->setSlug("buche-de-noel")->setName("bûche de noël")
                ->addMultipleChildCategories($bucheDeNoel),
            (new Category())->setSlug("ile-flottante")->setName("île flottante"),
            (new Category())->setSlug("mille-feuille")->setName("mille-feuille"),
            (new Category())->setSlug("baba-au-rhum")->setName("baba au rhum"),
            (new Category())->setSlug("pudding")->setName("pudding"),
            (new Category())->setSlug("souffle-sucre")->setName("soufflé sucré"),
            (new Category())->setSlug("cheesecake")->setName("cheesecake"),
            (new Category())->setSlug("tiramisu")->setName("tiramisu"),
            (new Category())->setSlug("salade-de-fruits")->setName("salade de fruits")
                ->addMultipleChildCategories($saladeDeFruits),
            (new Category())->setSlug("compote")->setName("compote")
                ->addMultipleChildCategories($compote),
            (new Category())->setSlug("pomme-au-four")->setName("pomme au four"),
            (new Category())->setSlug("poire-au-sirop")->setName("poire au sirop"),
            (new Category())->setSlug("viennoiseries")->setName("viennoiseries")
                ->addMultipleChildCategories($viennoiseries),
            (new Category())->setSlug("brioche")->setName("brioche")
                ->addMultipleChildCategories($brioche),
            (new Category())->setSlug("gaufre")->setName("gaufre"),
            (new Category())->setSlug("dessert-glace")->setName("dessert glacé")
                ->addMultipleChildCategories($dessertGlace),
            (new Category())->setSlug("crepe")->setName("crêpe")
                ->addMultipleChildCategories($crepes),
            (new Category())->setSlug("pain-perdu")->setName("pain perdu"),
            (new Category())->setSlug("pain-d-epices")->setName("pain d'épices"),
            (new Category())->setSlug("desserts-d-halloween")->setName("desserts d'halloween"),
            (new Category())->setSlug("desserts-au-thermomix")->setName("desserts au thermomix"),
            (new Category())->setSlug("entremets")->setName("entremets"),
            (new Category())->setSlug("gâteaux-a-la-frangipane")->setName("gâteaux à la frangipane")
                ->addMultipleChildCategories($gateauxALaFrangipane),
            (new Category())->setSlug("omelette-sucree")->setName("omelette sucrée"),
            (new Category())->setSlug("petit-dejeuner")->setName("petit déjeuner")
                ->addMultipleChildCategories($petitDejeuner),
            (new Category())->setSlug("banoffee")->setName("banoffee"),
            (new Category())->setSlug("barre-de-cereales")->setName("barre de céréales"),
        );

        //Sub - 0
        $allCategoriesData = array(
            (new Category())->setSlug("aperitifs")->setName("apéritifs")
                ->addMultipleChildCategories($aperitifs),
            (new Category())->setSlug("entrees")->setName("entrées")
                ->addMultipleChildCategories($entrees),
            (new Category())->setSlug("plat-principal")->setName("plat principal")
                ->addMultipleChildCategories($platPrincipal),
            (new Category())->setSlug("dessert")->setName("dessert")
                ->addMultipleChildCategories($desserts),
        );

        foreach($allCategoriesData as $mainCategory){
            $manager->persist($mainCategory);
        }
        $manager->flush();

        /*
        (new Category())->setSlug("xxx")->setName("xxx"),
        */
    }
}