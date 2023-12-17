<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class HardcodedDataController extends Controller
{
    public function getCountries()
    {
        $countries = array();

        $countries[] = array(
            "num_code" => "702",
            "alpha_2_code" => "SG",
            "alpha_3_code" => "SGP",
            "en_short_name" => "Singapore",
            "nationality" => "Singaporean",
        );

        $countries[] = array(
            "num_code" => "458",
            "alpha_2_code" => "MY",
            "alpha_3_code" => "MYS",
            "en_short_name" => "Malaysia",
            "nationality" => "Malaysian",
        );

        $countries[] = array(
            "num_code" => "4",
            "alpha_2_code" => "AF",
            "alpha_3_code" => "AFG",
            "en_short_name" => "Afghanistan",
            "nationality" => "Afghan",
        );

        $countries[] = array(
            "num_code" => "248",
            "alpha_2_code" => "AX",
            "alpha_3_code" => "ALA",
            "en_short_name" => "Åland Islands",
            "nationality" => "Åland Island",
        );

        $countries[] = array(
            "num_code" => "8",
            "alpha_2_code" => "AL",
            "alpha_3_code" => "ALB",
            "en_short_name" => "Albania",
            "nationality" => "Albanian",
        );

        $countries[] = array(
            "num_code" => "12",
            "alpha_2_code" => "DZ",
            "alpha_3_code" => "DZA",
            "en_short_name" => "Algeria",
            "nationality" => "Algerian",
        );

        $countries[] = array(
            "num_code" => "16",
            "alpha_2_code" => "AS",
            "alpha_3_code" => "ASM",
            "en_short_name" => "American Samoa",
            "nationality" => "American Samoan",
        );

        $countries[] = array(
            "num_code" => "20",
            "alpha_2_code" => "AD",
            "alpha_3_code" => "AND",
            "en_short_name" => "Andorra",
            "nationality" => "Andorran",
        );

        $countries[] = array(
            "num_code" => "24",
            "alpha_2_code" => "AO",
            "alpha_3_code" => "AGO",
            "en_short_name" => "Angola",
            "nationality" => "Angolan",
        );

        $countries[] = array(
            "num_code" => "660",
            "alpha_2_code" => "AI",
            "alpha_3_code" => "AIA",
            "en_short_name" => "Anguilla",
            "nationality" => "Anguillan",
        );

        $countries[] = array(
            "num_code" => "10",
            "alpha_2_code" => "AQ",
            "alpha_3_code" => "ATA",
            "en_short_name" => "Antarctica",
            "nationality" => "Antarctic",
        );

        $countries[] = array(
            "num_code" => "28",
            "alpha_2_code" => "AG",
            "alpha_3_code" => "ATG",
            "en_short_name" => "Antigua and Barbuda",
            "nationality" => "Antiguan or Barbudan",
        );

        $countries[] = array(
            "num_code" => "32",
            "alpha_2_code" => "AR",
            "alpha_3_code" => "ARG",
            "en_short_name" => "Argentina",
            "nationality" => "Argentine",
        );

        $countries[] = array(
            "num_code" => "51",
            "alpha_2_code" => "AM",
            "alpha_3_code" => "ARM",
            "en_short_name" => "Armenia",
            "nationality" => "Armenian",
        );

        $countries[] = array(
            "num_code" => "533",
            "alpha_2_code" => "AW",
            "alpha_3_code" => "ABW",
            "en_short_name" => "Aruba",
            "nationality" => "Aruban",
        );

        $countries[] = array(
            "num_code" => "36",
            "alpha_2_code" => "AU",
            "alpha_3_code" => "AUS",
            "en_short_name" => "Australia",
            "nationality" => "Australian",
        );

        $countries[] = array(
            "num_code" => "40",
            "alpha_2_code" => "AT",
            "alpha_3_code" => "AUT",
            "en_short_name" => "Austria",
            "nationality" => "Austrian",
        );

        $countries[] = array(
            "num_code" => "31",
            "alpha_2_code" => "AZ",
            "alpha_3_code" => "AZE",
            "en_short_name" => "Azerbaijan",
            "nationality" => "Azerbaijani, Azeri",
        );

        $countries[] = array(
            "num_code" => "44",
            "alpha_2_code" => "BS",
            "alpha_3_code" => "BHS",
            "en_short_name" => "Bahamas",
            "nationality" => "Bahamian",
        );

        $countries[] = array(
            "num_code" => "48",
            "alpha_2_code" => "BH",
            "alpha_3_code" => "BHR",
            "en_short_name" => "Bahrain",
            "nationality" => "Bahraini",
        );

        $countries[] = array(
            "num_code" => "50",
            "alpha_2_code" => "BD",
            "alpha_3_code" => "BGD",
            "en_short_name" => "Bangladesh",
            "nationality" => "Bangladeshi",
        );

        $countries[] = array(
            "num_code" => "52",
            "alpha_2_code" => "BB",
            "alpha_3_code" => "BRB",
            "en_short_name" => "Barbados",
            "nationality" => "Barbadian",
        );

        $countries[] = array(
            "num_code" => "112",
            "alpha_2_code" => "BY",
            "alpha_3_code" => "BLR",
            "en_short_name" => "Belarus",
            "nationality" => "Belarusian",
        );

        $countries[] = array(
            "num_code" => "56",
            "alpha_2_code" => "BE",
            "alpha_3_code" => "BEL",
            "en_short_name" => "Belgium",
            "nationality" => "Belgian",
        );

        $countries[] = array(
            "num_code" => "84",
            "alpha_2_code" => "BZ",
            "alpha_3_code" => "BLZ",
            "en_short_name" => "Belize",
            "nationality" => "Belizean",
        );

        $countries[] = array(
            "num_code" => "204",
            "alpha_2_code" => "BJ",
            "alpha_3_code" => "BEN",
            "en_short_name" => "Benin",
            "nationality" => "Beninese, Beninois",
        );

        $countries[] = array(
            "num_code" => "60",
            "alpha_2_code" => "BM",
            "alpha_3_code" => "BMU",
            "en_short_name" => "Bermuda",
            "nationality" => "Bermudian, Bermudan",
        );

        $countries[] = array(
            "num_code" => "64",
            "alpha_2_code" => "BT",
            "alpha_3_code" => "BTN",
            "en_short_name" => "Bhutan",
            "nationality" => "Bhutanese",
        );

        $countries[] = array(
            "num_code" => "68",
            "alpha_2_code" => "BO",
            "alpha_3_code" => "BOL",
            "en_short_name" => "Bolivia (Plurinational State of)",
            "nationality" => "Bolivian",
        );

        $countries[] = array(
            "num_code" => "535",
            "alpha_2_code" => "BQ",
            "alpha_3_code" => "BES",
            "en_short_name" => "Bonaire, Sint Eustatius and Saba",
            "nationality" => "Bonaire",
        );

        $countries[] = array(
            "num_code" => "70",
            "alpha_2_code" => "BA",
            "alpha_3_code" => "BIH",
            "en_short_name" => "Bosnia and Herzegovina",
            "nationality" => "Bosnian or Herzegovinian",
        );

        $countries[] = array(
            "num_code" => "72",
            "alpha_2_code" => "BW",
            "alpha_3_code" => "BWA",
            "en_short_name" => "Botswana",
            "nationality" => "Motswana, Botswanan",
        );

        $countries[] = array(
            "num_code" => "74",
            "alpha_2_code" => "BV",
            "alpha_3_code" => "BVT",
            "en_short_name" => "Bouvet Island",
            "nationality" => "Bouvet Island",
        );

        $countries[] = array(
            "num_code" => "76",
            "alpha_2_code" => "BR",
            "alpha_3_code" => "BRA",
            "en_short_name" => "Brazil",
            "nationality" => "Brazilian",
        );

        $countries[] = array(
            "num_code" => "86",
            "alpha_2_code" => "IO",
            "alpha_3_code" => "IOT",
            "en_short_name" => "British Indian Ocean Territory",
            "nationality" => "BIOT",
        );

        $countries[] = array(
            "num_code" => "96",
            "alpha_2_code" => "BN",
            "alpha_3_code" => "BRN",
            "en_short_name" => "Brunei Darussalam",
            "nationality" => "Bruneian",
        );

        $countries[] = array(
            "num_code" => "100",
            "alpha_2_code" => "BG",
            "alpha_3_code" => "BGR",
            "en_short_name" => "Bulgaria",
            "nationality" => "Bulgarian",
        );

        $countries[] = array(
            "num_code" => "854",
            "alpha_2_code" => "BF",
            "alpha_3_code" => "BFA",
            "en_short_name" => "Burkina Faso",
            "nationality" => "Burkinabé",
        );

        $countries[] = array(
            "num_code" => "108",
            "alpha_2_code" => "BI",
            "alpha_3_code" => "BDI",
            "en_short_name" => "Burundi",
            "nationality" => "Burundian",
        );

        $countries[] = array(
            "num_code" => "132",
            "alpha_2_code" => "CV",
            "alpha_3_code" => "CPV",
            "en_short_name" => "Cabo Verde",
            "nationality" => "Cabo Verdean",
        );

        $countries[] = array(
            "num_code" => "116",
            "alpha_2_code" => "KH",
            "alpha_3_code" => "KHM",
            "en_short_name" => "Cambodia",
            "nationality" => "Cambodian",
        );

        $countries[] = array(
            "num_code" => "120",
            "alpha_2_code" => "CM",
            "alpha_3_code" => "CMR",
            "en_short_name" => "Cameroon",
            "nationality" => "Cameroonian",
        );

        $countries[] = array(
            "num_code" => "124",
            "alpha_2_code" => "CA",
            "alpha_3_code" => "CAN",
            "en_short_name" => "Canada",
            "nationality" => "Canadian",
        );

        $countries[] = array(
            "num_code" => "136",
            "alpha_2_code" => "KY",
            "alpha_3_code" => "CYM",
            "en_short_name" => "Cayman Islands",
            "nationality" => "Caymanian",
        );

        $countries[] = array(
            "num_code" => "140",
            "alpha_2_code" => "CF",
            "alpha_3_code" => "CAF",
            "en_short_name" => "Central African Republic",
            "nationality" => "Central African",
        );

        $countries[] = array(
            "num_code" => "148",
            "alpha_2_code" => "TD",
            "alpha_3_code" => "TCD",
            "en_short_name" => "Chad",
            "nationality" => "Chadian",
        );

        $countries[] = array(
            "num_code" => "152",
            "alpha_2_code" => "CL",
            "alpha_3_code" => "CHL",
            "en_short_name" => "Chile",
            "nationality" => "Chilean",
        );

        $countries[] = array(
            "num_code" => "156",
            "alpha_2_code" => "CN",
            "alpha_3_code" => "CHN",
            "en_short_name" => "China",
            "nationality" => "Chinese",
        );

        $countries[] = array(
            "num_code" => "162",
            "alpha_2_code" => "CX",
            "alpha_3_code" => "CXR",
            "en_short_name" => "Christmas Island",
            "nationality" => "Christmas Island",
        );

        $countries[] = array(
            "num_code" => "166",
            "alpha_2_code" => "CC",
            "alpha_3_code" => "CCK",
            "en_short_name" => "Cocos (Keeling) Islands",
            "nationality" => "Cocos Island",
        );

        $countries[] = array(
            "num_code" => "170",
            "alpha_2_code" => "CO",
            "alpha_3_code" => "COL",
            "en_short_name" => "Colombia",
            "nationality" => "Colombian",
        );

        $countries[] = array(
            "num_code" => "174",
            "alpha_2_code" => "KM",
            "alpha_3_code" => "COM",
            "en_short_name" => "Comoros",
            "nationality" => "Comoran, Comorian",
        );

        $countries[] = array(
            "num_code" => "178",
            "alpha_2_code" => "CG",
            "alpha_3_code" => "COG",
            "en_short_name" => "Congo (Republic of the)",
            "nationality" => "Congolese",
        );

        $countries[] = array(
            "num_code" => "180",
            "alpha_2_code" => "CD",
            "alpha_3_code" => "COD",
            "en_short_name" => "Congo (Democratic Republic of the)",
            "nationality" => "Congolese",
        );

        $countries[] = array(
            "num_code" => "184",
            "alpha_2_code" => "CK",
            "alpha_3_code" => "COK",
            "en_short_name" => "Cook Islands",
            "nationality" => "Cook Island",
        );

        $countries[] = array(
            "num_code" => "188",
            "alpha_2_code" => "CR",
            "alpha_3_code" => "CRI",
            "en_short_name" => "Costa Rica",
            "nationality" => "Costa Rican",
        );

        $countries[] = array(
            "num_code" => "384",
            "alpha_2_code" => "CI",
            "alpha_3_code" => "CIV",
            "en_short_name" => "Côte d'Ivoire",
            "nationality" => "Ivorian",
        );

        $countries[] = array(
            "num_code" => "191",
            "alpha_2_code" => "HR",
            "alpha_3_code" => "HRV",
            "en_short_name" => "Croatia",
            "nationality" => "Croatian",
        );

        $countries[] = array(
            "num_code" => "192",
            "alpha_2_code" => "CU",
            "alpha_3_code" => "CUB",
            "en_short_name" => "Cuba",
            "nationality" => "Cuban",
        );

        $countries[] = array(
            "num_code" => "531",
            "alpha_2_code" => "CW",
            "alpha_3_code" => "CUW",
            "en_short_name" => "Curaçao",
            "nationality" => "Curaçaoan",
        );

        $countries[] = array(
            "num_code" => "196",
            "alpha_2_code" => "CY",
            "alpha_3_code" => "CYP",
            "en_short_name" => "Cyprus",
            "nationality" => "Cypriot",
        );

        $countries[] = array(
            "num_code" => "203",
            "alpha_2_code" => "CZ",
            "alpha_3_code" => "CZE",
            "en_short_name" => "Czech Republic",
            "nationality" => "Czech",
        );

        $countries[] = array(
            "num_code" => "208",
            "alpha_2_code" => "DK",
            "alpha_3_code" => "DNK",
            "en_short_name" => "Denmark",
            "nationality" => "Danish",
        );

        $countries[] = array(
            "num_code" => "262",
            "alpha_2_code" => "DJ",
            "alpha_3_code" => "DJI",
            "en_short_name" => "Djibouti",
            "nationality" => "Djiboutian",
        );

        $countries[] = array(
            "num_code" => "212",
            "alpha_2_code" => "DM",
            "alpha_3_code" => "DMA",
            "en_short_name" => "Dominica",
            "nationality" => "Dominican",
        );

        $countries[] = array(
            "num_code" => "214",
            "alpha_2_code" => "DO",
            "alpha_3_code" => "DOM",
            "en_short_name" => "Dominican Republic",
            "nationality" => "Dominican",
        );

        $countries[] = array(
            "num_code" => "218",
            "alpha_2_code" => "EC",
            "alpha_3_code" => "ECU",
            "en_short_name" => "Ecuador",
            "nationality" => "Ecuadorian",
        );

        $countries[] = array(
            "num_code" => "818",
            "alpha_2_code" => "EG",
            "alpha_3_code" => "EGY",
            "en_short_name" => "Egypt",
            "nationality" => "Egyptian",
        );

        $countries[] = array(
            "num_code" => "222",
            "alpha_2_code" => "SV",
            "alpha_3_code" => "SLV",
            "en_short_name" => "El Salvador",
            "nationality" => "Salvadoran",
        );

        $countries[] = array(
            "num_code" => "226",
            "alpha_2_code" => "GQ",
            "alpha_3_code" => "GNQ",
            "en_short_name" => "Equatorial Guinea",
            "nationality" => "Equatorial Guinean, Equatoguinean",
        );

        $countries[] = array(
            "num_code" => "232",
            "alpha_2_code" => "ER",
            "alpha_3_code" => "ERI",
            "en_short_name" => "Eritrea",
            "nationality" => "Eritrean",
        );

        $countries[] = array(
            "num_code" => "233",
            "alpha_2_code" => "EE",
            "alpha_3_code" => "EST",
            "en_short_name" => "Estonia",
            "nationality" => "Estonian",
        );

        $countries[] = array(
            "num_code" => "231",
            "alpha_2_code" => "ET",
            "alpha_3_code" => "ETH",
            "en_short_name" => "Ethiopia",
            "nationality" => "Ethiopian",
        );

        $countries[] = array(
            "num_code" => "238",
            "alpha_2_code" => "FK",
            "alpha_3_code" => "FLK",
            "en_short_name" => "Falkland Islands (Malvinas)",
            "nationality" => "Falkland Island",
        );

        $countries[] = array(
            "num_code" => "234",
            "alpha_2_code" => "FO",
            "alpha_3_code" => "FRO",
            "en_short_name" => "Faroe Islands",
            "nationality" => "Faroese",
        );

        $countries[] = array(
            "num_code" => "242",
            "alpha_2_code" => "FJ",
            "alpha_3_code" => "FJI",
            "en_short_name" => "Fiji",
            "nationality" => "Fijian",
        );

        $countries[] = array(
            "num_code" => "246",
            "alpha_2_code" => "FI",
            "alpha_3_code" => "FIN",
            "en_short_name" => "Finland",
            "nationality" => "Finnish",
        );

        $countries[] = array(
            "num_code" => "250",
            "alpha_2_code" => "FR",
            "alpha_3_code" => "FRA",
            "en_short_name" => "France",
            "nationality" => "French",
        );

        $countries[] = array(
            "num_code" => "254",
            "alpha_2_code" => "GF",
            "alpha_3_code" => "GUF",
            "en_short_name" => "French Guiana",
            "nationality" => "French Guianese",
        );

        $countries[] = array(
            "num_code" => "258",
            "alpha_2_code" => "PF",
            "alpha_3_code" => "PYF",
            "en_short_name" => "French Polynesia",
            "nationality" => "French Polynesian",
        );

        $countries[] = array(
            "num_code" => "260",
            "alpha_2_code" => "TF",
            "alpha_3_code" => "ATF",
            "en_short_name" => "French Southern Territories",
            "nationality" => "French Southern Territories",
        );

        $countries[] = array(
            "num_code" => "266",
            "alpha_2_code" => "GA",
            "alpha_3_code" => "GAB",
            "en_short_name" => "Gabon",
            "nationality" => "Gabonese",
        );

        $countries[] = array(
            "num_code" => "270",
            "alpha_2_code" => "GM",
            "alpha_3_code" => "GMB",
            "en_short_name" => "Gambia",
            "nationality" => "Gambian",
        );

        $countries[] = array(
            "num_code" => "268",
            "alpha_2_code" => "GE",
            "alpha_3_code" => "GEO",
            "en_short_name" => "Georgia",
            "nationality" => "Georgian",
        );

        $countries[] = array(
            "num_code" => "276",
            "alpha_2_code" => "DE",
            "alpha_3_code" => "DEU",
            "en_short_name" => "Germany",
            "nationality" => "German",
        );

        $countries[] = array(
            "num_code" => "288",
            "alpha_2_code" => "GH",
            "alpha_3_code" => "GHA",
            "en_short_name" => "Ghana",
            "nationality" => "Ghanaian",
        );

        $countries[] = array(
            "num_code" => "292",
            "alpha_2_code" => "GI",
            "alpha_3_code" => "GIB",
            "en_short_name" => "Gibraltar",
            "nationality" => "Gibraltar",
        );

        $countries[] = array(
            "num_code" => "300",
            "alpha_2_code" => "GR",
            "alpha_3_code" => "GRC",
            "en_short_name" => "Greece",
            "nationality" => "Greek, Hellenic",
        );

        $countries[] = array(
            "num_code" => "304",
            "alpha_2_code" => "GL",
            "alpha_3_code" => "GRL",
            "en_short_name" => "Greenland",
            "nationality" => "Greenlandic",
        );

        $countries[] = array(
            "num_code" => "308",
            "alpha_2_code" => "GD",
            "alpha_3_code" => "GRD",
            "en_short_name" => "Grenada",
            "nationality" => "Grenadian",
        );

        $countries[] = array(
            "num_code" => "312",
            "alpha_2_code" => "GP",
            "alpha_3_code" => "GLP",
            "en_short_name" => "Guadeloupe",
            "nationality" => "Guadeloupe",
        );

        $countries[] = array(
            "num_code" => "316",
            "alpha_2_code" => "GU",
            "alpha_3_code" => "GUM",
            "en_short_name" => "Guam",
            "nationality" => "Guamanian, Guambat",
        );

        $countries[] = array(
            "num_code" => "320",
            "alpha_2_code" => "GT",
            "alpha_3_code" => "GTM",
            "en_short_name" => "Guatemala",
            "nationality" => "Guatemalan",
        );

        $countries[] = array(
            "num_code" => "831",
            "alpha_2_code" => "GG",
            "alpha_3_code" => "GGY",
            "en_short_name" => "Guernsey",
            "nationality" => "Channel Island",
        );

        $countries[] = array(
            "num_code" => "324",
            "alpha_2_code" => "GN",
            "alpha_3_code" => "GIN",
            "en_short_name" => "Guinea",
            "nationality" => "Guinean",
        );

        $countries[] = array(
            "num_code" => "624",
            "alpha_2_code" => "GW",
            "alpha_3_code" => "GNB",
            "en_short_name" => "Guinea-Bissau",
            "nationality" => "Bissau-Guinean",
        );

        $countries[] = array(
            "num_code" => "328",
            "alpha_2_code" => "GY",
            "alpha_3_code" => "GUY",
            "en_short_name" => "Guyana",
            "nationality" => "Guyanese",
        );

        $countries[] = array(
            "num_code" => "332",
            "alpha_2_code" => "HT",
            "alpha_3_code" => "HTI",
            "en_short_name" => "Haiti",
            "nationality" => "Haitian",
        );

        $countries[] = array(
            "num_code" => "334",
            "alpha_2_code" => "HM",
            "alpha_3_code" => "HMD",
            "en_short_name" => "Heard Island and McDonald Islands",
            "nationality" => "Heard Island or McDonald Islands",
        );

        $countries[] = array(
            "num_code" => "336",
            "alpha_2_code" => "VA",
            "alpha_3_code" => "VAT",
            "en_short_name" => "Vatican City State",
            "nationality" => "Vatican",
        );

        $countries[] = array(
            "num_code" => "340",
            "alpha_2_code" => "HN",
            "alpha_3_code" => "HND",
            "en_short_name" => "Honduras",
            "nationality" => "Honduran",
        );

        $countries[] = array(
            "num_code" => "344",
            "alpha_2_code" => "HK",
            "alpha_3_code" => "HKG",
            "en_short_name" => "Hong Kong",
            "nationality" => "Hong Kong, Hong Kongese",
        );

        $countries[] = array(
            "num_code" => "348",
            "alpha_2_code" => "HU",
            "alpha_3_code" => "HUN",
            "en_short_name" => "Hungary",
            "nationality" => "Hungarian, Magyar",
        );

        $countries[] = array(
            "num_code" => "352",
            "alpha_2_code" => "IS",
            "alpha_3_code" => "ISL",
            "en_short_name" => "Iceland",
            "nationality" => "Icelandic",
        );

        $countries[] = array(
            "num_code" => "356",
            "alpha_2_code" => "IN",
            "alpha_3_code" => "IND",
            "en_short_name" => "India",
            "nationality" => "Indian",
        );

        $countries[] = array(
            "num_code" => "360",
            "alpha_2_code" => "ID",
            "alpha_3_code" => "IDN",
            "en_short_name" => "Indonesia",
            "nationality" => "Indonesian",
        );

        $countries[] = array(
            "num_code" => "364",
            "alpha_2_code" => "IR",
            "alpha_3_code" => "IRN",
            "en_short_name" => "Iran",
            "nationality" => "Iranian, Persian",
        );

        $countries[] = array(
            "num_code" => "368",
            "alpha_2_code" => "IQ",
            "alpha_3_code" => "IRQ",
            "en_short_name" => "Iraq",
            "nationality" => "Iraqi",
        );

        $countries[] = array(
            "num_code" => "372",
            "alpha_2_code" => "IE",
            "alpha_3_code" => "IRL",
            "en_short_name" => "Ireland",
            "nationality" => "Irish",
        );

        $countries[] = array(
            "num_code" => "833",
            "alpha_2_code" => "IM",
            "alpha_3_code" => "IMN",
            "en_short_name" => "Isle of Man",
            "nationality" => "Manx",
        );

        $countries[] = array(
            "num_code" => "376",
            "alpha_2_code" => "IL",
            "alpha_3_code" => "ISR",
            "en_short_name" => "Israel",
            "nationality" => "Israeli",
        );

        $countries[] = array(
            "num_code" => "380",
            "alpha_2_code" => "IT",
            "alpha_3_code" => "ITA",
            "en_short_name" => "Italy",
            "nationality" => "Italian",
        );

        $countries[] = array(
            "num_code" => "388",
            "alpha_2_code" => "JM",
            "alpha_3_code" => "JAM",
            "en_short_name" => "Jamaica",
            "nationality" => "Jamaican",
        );

        $countries[] = array(
            "num_code" => "392",
            "alpha_2_code" => "JP",
            "alpha_3_code" => "JPN",
            "en_short_name" => "Japan",
            "nationality" => "Japanese",
        );

        $countries[] = array(
            "num_code" => "832",
            "alpha_2_code" => "JE",
            "alpha_3_code" => "JEY",
            "en_short_name" => "Jersey",
            "nationality" => "Channel Island",
        );

        $countries[] = array(
            "num_code" => "400",
            "alpha_2_code" => "JO",
            "alpha_3_code" => "JOR",
            "en_short_name" => "Jordan",
            "nationality" => "Jordanian",
        );

        $countries[] = array(
            "num_code" => "398",
            "alpha_2_code" => "KZ",
            "alpha_3_code" => "KAZ",
            "en_short_name" => "Kazakhstan",
            "nationality" => "Kazakhstani, Kazakh",
        );

        $countries[] = array(
            "num_code" => "404",
            "alpha_2_code" => "KE",
            "alpha_3_code" => "KEN",
            "en_short_name" => "Kenya",
            "nationality" => "Kenyan",
        );

        $countries[] = array(
            "num_code" => "296",
            "alpha_2_code" => "KI",
            "alpha_3_code" => "KIR",
            "en_short_name" => "Kiribati",
            "nationality" => "I-Kiribati",
        );

        $countries[] = array(
            "num_code" => "408",
            "alpha_2_code" => "KP",
            "alpha_3_code" => "PRK",
            "en_short_name" => "Korea (Democratic People's Republic of)",
            "nationality" => "North Korean",
        );

        $countries[] = array(
            "num_code" => "410",
            "alpha_2_code" => "KR",
            "alpha_3_code" => "KOR",
            "en_short_name" => "Korea (Republic of)",
            "nationality" => "South Korean",
        );

        $countries[] = array(
            "num_code" => "414",
            "alpha_2_code" => "KW",
            "alpha_3_code" => "KWT",
            "en_short_name" => "Kuwait",
            "nationality" => "Kuwaiti",
        );

        $countries[] = array(
            "num_code" => "417",
            "alpha_2_code" => "KG",
            "alpha_3_code" => "KGZ",
            "en_short_name" => "Kyrgyzstan",
            "nationality" => "Kyrgyzstani, Kyrgyz, Kirgiz, Kirghiz",
        );

        $countries[] = array(
            "num_code" => "418",
            "alpha_2_code" => "LA",
            "alpha_3_code" => "LAO",
            "en_short_name" => "Lao People's Democratic Republic",
            "nationality" => "Lao, Laotian",
        );

        $countries[] = array(
            "num_code" => "428",
            "alpha_2_code" => "LV",
            "alpha_3_code" => "LVA",
            "en_short_name" => "Latvia",
            "nationality" => "Latvian",
        );

        $countries[] = array(
            "num_code" => "422",
            "alpha_2_code" => "LB",
            "alpha_3_code" => "LBN",
            "en_short_name" => "Lebanon",
            "nationality" => "Lebanese",
        );

        $countries[] = array(
            "num_code" => "426",
            "alpha_2_code" => "LS",
            "alpha_3_code" => "LSO",
            "en_short_name" => "Lesotho",
            "nationality" => "Basotho",
        );

        $countries[] = array(
            "num_code" => "430",
            "alpha_2_code" => "LR",
            "alpha_3_code" => "LBR",
            "en_short_name" => "Liberia",
            "nationality" => "Liberian",
        );

        $countries[] = array(
            "num_code" => "434",
            "alpha_2_code" => "LY",
            "alpha_3_code" => "LBY",
            "en_short_name" => "Libya",
            "nationality" => "Libyan",
        );

        $countries[] = array(
            "num_code" => "438",
            "alpha_2_code" => "LI",
            "alpha_3_code" => "LIE",
            "en_short_name" => "Liechtenstein",
            "nationality" => "Liechtenstein",
        );

        $countries[] = array(
            "num_code" => "440",
            "alpha_2_code" => "LT",
            "alpha_3_code" => "LTU",
            "en_short_name" => "Lithuania",
            "nationality" => "Lithuanian",
        );

        $countries[] = array(
            "num_code" => "442",
            "alpha_2_code" => "LU",
            "alpha_3_code" => "LUX",
            "en_short_name" => "Luxembourg",
            "nationality" => "Luxembourg, Luxembourgish",
        );

        $countries[] = array(
            "num_code" => "446",
            "alpha_2_code" => "MO",
            "alpha_3_code" => "MAC",
            "en_short_name" => "Macao",
            "nationality" => "Macanese, Chinese",
        );

        $countries[] = array(
            "num_code" => "807",
            "alpha_2_code" => "MK",
            "alpha_3_code" => "MKD",
            "en_short_name" => "Macedonia (the former Yugoslav Republic of)",
            "nationality" => "Macedonian",
        );

        $countries[] = array(
            "num_code" => "450",
            "alpha_2_code" => "MG",
            "alpha_3_code" => "MDG",
            "en_short_name" => "Madagascar",
            "nationality" => "Malagasy",
        );

        $countries[] = array(
            "num_code" => "454",
            "alpha_2_code" => "MW",
            "alpha_3_code" => "MWI",
            "en_short_name" => "Malawi",
            "nationality" => "Malawian",
        );

        $countries[] = array(
            "num_code" => "462",
            "alpha_2_code" => "MV",
            "alpha_3_code" => "MDV",
            "en_short_name" => "Maldives",
            "nationality" => "Maldivian",
        );

        $countries[] = array(
            "num_code" => "466",
            "alpha_2_code" => "ML",
            "alpha_3_code" => "MLI",
            "en_short_name" => "Mali",
            "nationality" => "Malian, Malinese",
        );

        $countries[] = array(
            "num_code" => "470",
            "alpha_2_code" => "MT",
            "alpha_3_code" => "MLT",
            "en_short_name" => "Malta",
            "nationality" => "Maltese",
        );

        $countries[] = array(
            "num_code" => "584",
            "alpha_2_code" => "MH",
            "alpha_3_code" => "MHL",
            "en_short_name" => "Marshall Islands",
            "nationality" => "Marshallese",
        );

        $countries[] = array(
            "num_code" => "474",
            "alpha_2_code" => "MQ",
            "alpha_3_code" => "MTQ",
            "en_short_name" => "Martinique",
            "nationality" => "Martiniquais, Martinican",
        );

        $countries[] = array(
            "num_code" => "478",
            "alpha_2_code" => "MR",
            "alpha_3_code" => "MRT",
            "en_short_name" => "Mauritania",
            "nationality" => "Mauritanian",
        );

        $countries[] = array(
            "num_code" => "480",
            "alpha_2_code" => "MU",
            "alpha_3_code" => "MUS",
            "en_short_name" => "Mauritius",
            "nationality" => "Mauritian",
        );

        $countries[] = array(
            "num_code" => "175",
            "alpha_2_code" => "YT",
            "alpha_3_code" => "MYT",
            "en_short_name" => "Mayotte",
            "nationality" => "Mahoran",
        );

        $countries[] = array(
            "num_code" => "484",
            "alpha_2_code" => "MX",
            "alpha_3_code" => "MEX",
            "en_short_name" => "Mexico",
            "nationality" => "Mexican",
        );

        $countries[] = array(
            "num_code" => "583",
            "alpha_2_code" => "FM",
            "alpha_3_code" => "FSM",
            "en_short_name" => "Micronesia (Federated States of)",
            "nationality" => "Micronesian",
        );

        $countries[] = array(
            "num_code" => "498",
            "alpha_2_code" => "MD",
            "alpha_3_code" => "MDA",
            "en_short_name" => "Moldova (Republic of)",
            "nationality" => "Moldovan",
        );

        $countries[] = array(
            "num_code" => "492",
            "alpha_2_code" => "MC",
            "alpha_3_code" => "MCO",
            "en_short_name" => "Monaco",
            "nationality" => "Monégasque, Monacan",
        );

        $countries[] = array(
            "num_code" => "496",
            "alpha_2_code" => "MN",
            "alpha_3_code" => "MNG",
            "en_short_name" => "Mongolia",
            "nationality" => "Mongolian",
        );

        $countries[] = array(
            "num_code" => "499",
            "alpha_2_code" => "ME",
            "alpha_3_code" => "MNE",
            "en_short_name" => "Montenegro",
            "nationality" => "Montenegrin",
        );

        $countries[] = array(
            "num_code" => "500",
            "alpha_2_code" => "MS",
            "alpha_3_code" => "MSR",
            "en_short_name" => "Montserrat",
            "nationality" => "Montserratian",
        );

        $countries[] = array(
            "num_code" => "504",
            "alpha_2_code" => "MA",
            "alpha_3_code" => "MAR",
            "en_short_name" => "Morocco",
            "nationality" => "Moroccan",
        );

        $countries[] = array(
            "num_code" => "508",
            "alpha_2_code" => "MZ",
            "alpha_3_code" => "MOZ",
            "en_short_name" => "Mozambique",
            "nationality" => "Mozambican",
        );

        $countries[] = array(
            "num_code" => "104",
            "alpha_2_code" => "MM",
            "alpha_3_code" => "MMR",
            "en_short_name" => "Myanmar",
            "nationality" => "Burmese",
        );

        $countries[] = array(
            "num_code" => "516",
            "alpha_2_code" => "NA",
            "alpha_3_code" => "NAM",
            "en_short_name" => "Namibia",
            "nationality" => "Namibian",
        );

        $countries[] = array(
            "num_code" => "520",
            "alpha_2_code" => "NR",
            "alpha_3_code" => "NRU",
            "en_short_name" => "Nauru",
            "nationality" => "Nauruan",
        );

        $countries[] = array(
            "num_code" => "524",
            "alpha_2_code" => "NP",
            "alpha_3_code" => "NPL",
            "en_short_name" => "Nepal",
            "nationality" => "Nepali, Nepalese",
        );

        $countries[] = array(
            "num_code" => "528",
            "alpha_2_code" => "NL",
            "alpha_3_code" => "NLD",
            "en_short_name" => "Netherlands",
            "nationality" => "Dutch, Netherlandic",
        );

        $countries[] = array(
            "num_code" => "540",
            "alpha_2_code" => "NC",
            "alpha_3_code" => "NCL",
            "en_short_name" => "New Caledonia",
            "nationality" => "New Caledonian",
        );

        $countries[] = array(
            "num_code" => "554",
            "alpha_2_code" => "NZ",
            "alpha_3_code" => "NZL",
            "en_short_name" => "New Zealand",
            "nationality" => "New Zealand, NZ",
        );

        $countries[] = array(
            "num_code" => "558",
            "alpha_2_code" => "NI",
            "alpha_3_code" => "NIC",
            "en_short_name" => "Nicaragua",
            "nationality" => "Nicaraguan",
        );

        $countries[] = array(
            "num_code" => "562",
            "alpha_2_code" => "NE",
            "alpha_3_code" => "NER",
            "en_short_name" => "Niger",
            "nationality" => "Nigerien",
        );

        $countries[] = array(
            "num_code" => "566",
            "alpha_2_code" => "NG",
            "alpha_3_code" => "NGA",
            "en_short_name" => "Nigeria",
            "nationality" => "Nigerian",
        );

        $countries[] = array(
            "num_code" => "570",
            "alpha_2_code" => "NU",
            "alpha_3_code" => "NIU",
            "en_short_name" => "Niue",
            "nationality" => "Niuean",
        );

        $countries[] = array(
            "num_code" => "574",
            "alpha_2_code" => "NF",
            "alpha_3_code" => "NFK",
            "en_short_name" => "Norfolk Island",
            "nationality" => "Norfolk Island",
        );

        $countries[] = array(
            "num_code" => "580",
            "alpha_2_code" => "MP",
            "alpha_3_code" => "MNP",
            "en_short_name" => "Northern Mariana Islands",
            "nationality" => "Northern Marianan",
        );

        $countries[] = array(
            "num_code" => "578",
            "alpha_2_code" => "NO",
            "alpha_3_code" => "NOR",
            "en_short_name" => "Norway",
            "nationality" => "Norwegian",
        );

        $countries[] = array(
            "num_code" => "512",
            "alpha_2_code" => "OM",
            "alpha_3_code" => "OMN",
            "en_short_name" => "Oman",
            "nationality" => "Omani",
        );

        $countries[] = array(
            "num_code" => "586",
            "alpha_2_code" => "PK",
            "alpha_3_code" => "PAK",
            "en_short_name" => "Pakistan",
            "nationality" => "Pakistani",
        );

        $countries[] = array(
            "num_code" => "585",
            "alpha_2_code" => "PW",
            "alpha_3_code" => "PLW",
            "en_short_name" => "Palau",
            "nationality" => "Palauan",
        );

        $countries[] = array(
            "num_code" => "275",
            "alpha_2_code" => "PS",
            "alpha_3_code" => "PSE",
            "en_short_name" => "Palestine, State of",
            "nationality" => "Palestinian",
        );

        $countries[] = array(
            "num_code" => "591",
            "alpha_2_code" => "PA",
            "alpha_3_code" => "PAN",
            "en_short_name" => "Panama",
            "nationality" => "Panamanian",
        );

        $countries[] = array(
            "num_code" => "598",
            "alpha_2_code" => "PG",
            "alpha_3_code" => "PNG",
            "en_short_name" => "Papua New Guinea",
            "nationality" => "Papua New Guinean, Papuan",
        );

        $countries[] = array(
            "num_code" => "600",
            "alpha_2_code" => "PY",
            "alpha_3_code" => "PRY",
            "en_short_name" => "Paraguay",
            "nationality" => "Paraguayan",
        );

        $countries[] = array(
            "num_code" => "604",
            "alpha_2_code" => "PE",
            "alpha_3_code" => "PER",
            "en_short_name" => "Peru",
            "nationality" => "Peruvian",
        );

        $countries[] = array(
            "num_code" => "608",
            "alpha_2_code" => "PH",
            "alpha_3_code" => "PHL",
            "en_short_name" => "Philippines",
            "nationality" => "Philippine, Filipino",
        );

        $countries[] = array(
            "num_code" => "612",
            "alpha_2_code" => "PN",
            "alpha_3_code" => "PCN",
            "en_short_name" => "Pitcairn",
            "nationality" => "Pitcairn Island",
        );

        $countries[] = array(
            "num_code" => "616",
            "alpha_2_code" => "PL",
            "alpha_3_code" => "POL",
            "en_short_name" => "Poland",
            "nationality" => "Polish",
        );

        $countries[] = array(
            "num_code" => "620",
            "alpha_2_code" => "PT",
            "alpha_3_code" => "PRT",
            "en_short_name" => "Portugal",
            "nationality" => "Portuguese",
        );

        $countries[] = array(
            "num_code" => "630",
            "alpha_2_code" => "PR",
            "alpha_3_code" => "PRI",
            "en_short_name" => "Puerto Rico",
            "nationality" => "Puerto Rican",
        );

        $countries[] = array(
            "num_code" => "634",
            "alpha_2_code" => "QA",
            "alpha_3_code" => "QAT",
            "en_short_name" => "Qatar",
            "nationality" => "Qatari",
        );

        $countries[] = array(
            "num_code" => "638",
            "alpha_2_code" => "RE",
            "alpha_3_code" => "REU",
            "en_short_name" => "Réunion",
            "nationality" => "Réunionese, Réunionnais",
        );

        $countries[] = array(
            "num_code" => "642",
            "alpha_2_code" => "RO",
            "alpha_3_code" => "ROU",
            "en_short_name" => "Romania",
            "nationality" => "Romanian",
        );

        $countries[] = array(
            "num_code" => "643",
            "alpha_2_code" => "RU",
            "alpha_3_code" => "RUS",
            "en_short_name" => "Russian Federation",
            "nationality" => "Russian",
        );

        $countries[] = array(
            "num_code" => "646",
            "alpha_2_code" => "RW",
            "alpha_3_code" => "RWA",
            "en_short_name" => "Rwanda",
            "nationality" => "Rwandan",
        );

        $countries[] = array(
            "num_code" => "652",
            "alpha_2_code" => "BL",
            "alpha_3_code" => "BLM",
            "en_short_name" => "Saint Barthélemy",
            "nationality" => "Barthélemois",
        );

        $countries[] = array(
            "num_code" => "654",
            "alpha_2_code" => "SH",
            "alpha_3_code" => "SHN",
            "en_short_name" => "Saint Helena, Ascension and Tristan da Cunha",
            "nationality" => "Saint Helenian",
        );

        $countries[] = array(
            "num_code" => "659",
            "alpha_2_code" => "KN",
            "alpha_3_code" => "KNA",
            "en_short_name" => "Saint Kitts and Nevis",
            "nationality" => "Kittitian or Nevisian",
        );

        $countries[] = array(
            "num_code" => "662",
            "alpha_2_code" => "LC",
            "alpha_3_code" => "LCA",
            "en_short_name" => "Saint Lucia",
            "nationality" => "Saint Lucian",
        );

        $countries[] = array(
            "num_code" => "663",
            "alpha_2_code" => "MF",
            "alpha_3_code" => "MAF",
            "en_short_name" => "Saint Martin (French part)",
            "nationality" => "Saint-Martinoise",
        );

        $countries[] = array(
            "num_code" => "666",
            "alpha_2_code" => "PM",
            "alpha_3_code" => "SPM",
            "en_short_name" => "Saint Pierre and Miquelon",
            "nationality" => "Saint-Pierrais or Miquelonnais",
        );

        $countries[] = array(
            "num_code" => "670",
            "alpha_2_code" => "VC",
            "alpha_3_code" => "VCT",
            "en_short_name" => "Saint Vincent and the Grenadines",
            "nationality" => "Saint Vincentian, Vincentian",
        );

        $countries[] = array(
            "num_code" => "882",
            "alpha_2_code" => "WS",
            "alpha_3_code" => "WSM",
            "en_short_name" => "Samoa",
            "nationality" => "Samoan",
        );

        $countries[] = array(
            "num_code" => "674",
            "alpha_2_code" => "SM",
            "alpha_3_code" => "SMR",
            "en_short_name" => "San Marino",
            "nationality" => "Sammarinese",
        );

        $countries[] = array(
            "num_code" => "678",
            "alpha_2_code" => "ST",
            "alpha_3_code" => "STP",
            "en_short_name" => "Sao Tome and Principe",
            "nationality" => "São Toméan",
        );

        $countries[] = array(
            "num_code" => "682",
            "alpha_2_code" => "SA",
            "alpha_3_code" => "SAU",
            "en_short_name" => "Saudi Arabia",
            "nationality" => "Saudi, Saudi Arabian",
        );

        $countries[] = array(
            "num_code" => "686",
            "alpha_2_code" => "SN",
            "alpha_3_code" => "SEN",
            "en_short_name" => "Senegal",
            "nationality" => "Senegalese",
        );

        $countries[] = array(
            "num_code" => "688",
            "alpha_2_code" => "RS",
            "alpha_3_code" => "SRB",
            "en_short_name" => "Serbia",
            "nationality" => "Serbian",
        );

        $countries[] = array(
            "num_code" => "690",
            "alpha_2_code" => "SC",
            "alpha_3_code" => "SYC",
            "en_short_name" => "Seychelles",
            "nationality" => "Seychellois",
        );

        $countries[] = array(
            "num_code" => "694",
            "alpha_2_code" => "SL",
            "alpha_3_code" => "SLE",
            "en_short_name" => "Sierra Leone",
            "nationality" => "Sierra Leonean",
        );

        $countries[] = array(
            "num_code" => "534",
            "alpha_2_code" => "SX",
            "alpha_3_code" => "SXM",
            "en_short_name" => "Sint Maarten (Dutch part)",
            "nationality" => "Sint Maarten",
        );

        $countries[] = array(
            "num_code" => "703",
            "alpha_2_code" => "SK",
            "alpha_3_code" => "SVK",
            "en_short_name" => "Slovakia",
            "nationality" => "Slovak",
        );

        $countries[] = array(
            "num_code" => "705",
            "alpha_2_code" => "SI",
            "alpha_3_code" => "SVN",
            "en_short_name" => "Slovenia",
            "nationality" => "Slovenian, Slovene",
        );

        $countries[] = array(
            "num_code" => "90",
            "alpha_2_code" => "SB",
            "alpha_3_code" => "SLB",
            "en_short_name" => "Solomon Islands",
            "nationality" => "Solomon Island",
        );

        $countries[] = array(
            "num_code" => "706",
            "alpha_2_code" => "SO",
            "alpha_3_code" => "SOM",
            "en_short_name" => "Somalia",
            "nationality" => "Somali, Somalian",
        );

        $countries[] = array(
            "num_code" => "710",
            "alpha_2_code" => "ZA",
            "alpha_3_code" => "ZAF",
            "en_short_name" => "South Africa",
            "nationality" => "South African",
        );

        $countries[] = array(
            "num_code" => "239",
            "alpha_2_code" => "GS",
            "alpha_3_code" => "SGS",
            "en_short_name" => "South Georgia and the South Sandwich Islands",
            "nationality" => "South Georgia or South Sandwich Islands",
        );

        $countries[] = array(
            "num_code" => "728",
            "alpha_2_code" => "SS",
            "alpha_3_code" => "SSD",
            "en_short_name" => "South Sudan",
            "nationality" => "South Sudanese",
        );

        $countries[] = array(
            "num_code" => "724",
            "alpha_2_code" => "ES",
            "alpha_3_code" => "ESP",
            "en_short_name" => "Spain",
            "nationality" => "Spanish",
        );

        $countries[] = array(
            "num_code" => "144",
            "alpha_2_code" => "LK",
            "alpha_3_code" => "LKA",
            "en_short_name" => "Sri Lanka",
            "nationality" => "Sri Lankan",
        );

        $countries[] = array(
            "num_code" => "729",
            "alpha_2_code" => "SD",
            "alpha_3_code" => "SDN",
            "en_short_name" => "Sudan",
            "nationality" => "Sudanese",
        );

        $countries[] = array(
            "num_code" => "740",
            "alpha_2_code" => "SR",
            "alpha_3_code" => "SUR",
            "en_short_name" => "Suriname",
            "nationality" => "Surinamese",
        );

        $countries[] = array(
            "num_code" => "744",
            "alpha_2_code" => "SJ",
            "alpha_3_code" => "SJM",
            "en_short_name" => "Svalbard and Jan Mayen",
            "nationality" => "Svalbard",
        );

        $countries[] = array(
            "num_code" => "748",
            "alpha_2_code" => "SZ",
            "alpha_3_code" => "SWZ",
            "en_short_name" => "Swaziland",
            "nationality" => "Swazi",
        );

        $countries[] = array(
            "num_code" => "752",
            "alpha_2_code" => "SE",
            "alpha_3_code" => "SWE",
            "en_short_name" => "Sweden",
            "nationality" => "Swedish",
        );

        $countries[] = array(
            "num_code" => "756",
            "alpha_2_code" => "CH",
            "alpha_3_code" => "CHE",
            "en_short_name" => "Switzerland",
            "nationality" => "Swiss",
        );

        $countries[] = array(
            "num_code" => "760",
            "alpha_2_code" => "SY",
            "alpha_3_code" => "SYR",
            "en_short_name" => "Syrian Arab Republic",
            "nationality" => "Syrian",
        );

        $countries[] = array(
            "num_code" => "158",
            "alpha_2_code" => "TW",
            "alpha_3_code" => "TWN",
            "en_short_name" => "Taiwan, Province of China",
            "nationality" => "Chinese, Taiwanese",
        );

        $countries[] = array(
            "num_code" => "762",
            "alpha_2_code" => "TJ",
            "alpha_3_code" => "TJK",
            "en_short_name" => "Tajikistan",
            "nationality" => "Tajikistani",
        );

        $countries[] = array(
            "num_code" => "834",
            "alpha_2_code" => "TZ",
            "alpha_3_code" => "TZA",
            "en_short_name" => "Tanzania, United Republic of",
            "nationality" => "Tanzanian",
        );

        $countries[] = array(
            "num_code" => "764",
            "alpha_2_code" => "TH",
            "alpha_3_code" => "THA",
            "en_short_name" => "Thailand",
            "nationality" => "Thai",
        );

        $countries[] = array(
            "num_code" => "626",
            "alpha_2_code" => "TL",
            "alpha_3_code" => "TLS",
            "en_short_name" => "Timor-Leste",
            "nationality" => "Timorese",
        );

        $countries[] = array(
            "num_code" => "768",
            "alpha_2_code" => "TG",
            "alpha_3_code" => "TGO",
            "en_short_name" => "Togo",
            "nationality" => "Togolese",
        );

        $countries[] = array(
            "num_code" => "772",
            "alpha_2_code" => "TK",
            "alpha_3_code" => "TKL",
            "en_short_name" => "Tokelau",
            "nationality" => "Tokelauan",
        );

        $countries[] = array(
            "num_code" => "776",
            "alpha_2_code" => "TO",
            "alpha_3_code" => "TON",
            "en_short_name" => "Tonga",
            "nationality" => "Tongan",
        );

        $countries[] = array(
            "num_code" => "780",
            "alpha_2_code" => "TT",
            "alpha_3_code" => "TTO",
            "en_short_name" => "Trinidad and Tobago",
            "nationality" => "Trinidadian or Tobagonian",
        );

        $countries[] = array(
            "num_code" => "788",
            "alpha_2_code" => "TN",
            "alpha_3_code" => "TUN",
            "en_short_name" => "Tunisia",
            "nationality" => "Tunisian",
        );

        $countries[] = array(
            "num_code" => "792",
            "alpha_2_code" => "TR",
            "alpha_3_code" => "TUR",
            "en_short_name" => "Turkey",
            "nationality" => "Turkish",
        );

        $countries[] = array(
            "num_code" => "795",
            "alpha_2_code" => "TM",
            "alpha_3_code" => "TKM",
            "en_short_name" => "Turkmenistan",
            "nationality" => "Turkmen",
        );

        $countries[] = array(
            "num_code" => "796",
            "alpha_2_code" => "TC",
            "alpha_3_code" => "TCA",
            "en_short_name" => "Turks and Caicos Islands",
            "nationality" => "Turks and Caicos Island",
        );

        $countries[] = array(
            "num_code" => "798",
            "alpha_2_code" => "TV",
            "alpha_3_code" => "TUV",
            "en_short_name" => "Tuvalu",
            "nationality" => "Tuvaluan",
        );

        $countries[] = array(
            "num_code" => "800",
            "alpha_2_code" => "UG",
            "alpha_3_code" => "UGA",
            "en_short_name" => "Uganda",
            "nationality" => "Ugandan",
        );

        $countries[] = array(
            "num_code" => "804",
            "alpha_2_code" => "UA",
            "alpha_3_code" => "UKR",
            "en_short_name" => "Ukraine",
            "nationality" => "Ukrainian",
        );

        $countries[] = array(
            "num_code" => "784",
            "alpha_2_code" => "AE",
            "alpha_3_code" => "ARE",
            "en_short_name" => "United Arab Emirates",
            "nationality" => "Emirati, Emirian, Emiri",
        );

        $countries[] = array(
            "num_code" => "826",
            "alpha_2_code" => "GB",
            "alpha_3_code" => "GBR",
            "en_short_name" => "United Kingdom of Great Britain and Northern Ireland",
            "nationality" => "British, UK",
        );

        $countries[] = array(
            "num_code" => "581",
            "alpha_2_code" => "UM",
            "alpha_3_code" => "UMI",
            "en_short_name" => "United States Minor Outlying Islands",
            "nationality" => "American",
        );

        $countries[] = array(
            "num_code" => "840",
            "alpha_2_code" => "US",
            "alpha_3_code" => "USA",
            "en_short_name" => "United States of America",
            "nationality" => "American",
        );

        $countries[] = array(
            "num_code" => "858",
            "alpha_2_code" => "UY",
            "alpha_3_code" => "URY",
            "en_short_name" => "Uruguay",
            "nationality" => "Uruguayan",
        );

        $countries[] = array(
            "num_code" => "860",
            "alpha_2_code" => "UZ",
            "alpha_3_code" => "UZB",
            "en_short_name" => "Uzbekistan",
            "nationality" => "Uzbekistani, Uzbek",
        );

        $countries[] = array(
            "num_code" => "548",
            "alpha_2_code" => "VU",
            "alpha_3_code" => "VUT",
            "en_short_name" => "Vanuatu",
            "nationality" => "Ni-Vanuatu, Vanuatuan",
        );

        $countries[] = array(
            "num_code" => "862",
            "alpha_2_code" => "VE",
            "alpha_3_code" => "VEN",
            "en_short_name" => "Venezuela (Bolivarian Republic of)",
            "nationality" => "Venezuelan",
        );

        $countries[] = array(
            "num_code" => "704",
            "alpha_2_code" => "VN",
            "alpha_3_code" => "VNM",
            "en_short_name" => "Vietnam",
            "nationality" => "Vietnamese",
        );

        $countries[] = array(
            "num_code" => "92",
            "alpha_2_code" => "VG",
            "alpha_3_code" => "VGB",
            "en_short_name" => "Virgin Islands (British)",
            "nationality" => "British Virgin Island",
        );

        $countries[] = array(
            "num_code" => "850",
            "alpha_2_code" => "VI",
            "alpha_3_code" => "VIR",
            "en_short_name" => "Virgin Islands (U.S.)",
            "nationality" => "U.S. Virgin Island",
        );

        $countries[] = array(
            "num_code" => "876",
            "alpha_2_code" => "WF",
            "alpha_3_code" => "WLF",
            "en_short_name" => "Wallis and Futuna",
            "nationality" => "Wallis and Futuna, Wallisian or Futunan",
        );

        $countries[] = array(
            "num_code" => "732",
            "alpha_2_code" => "EH",
            "alpha_3_code" => "ESH",
            "en_short_name" => "Western Sahara",
            "nationality" => "Sahrawi, Sahrawian, Sahraouian",
        );

        $countries[] = array(
            "num_code" => "887",
            "alpha_2_code" => "YE",
            "alpha_3_code" => "YEM",
            "en_short_name" => "Yemen",
            "nationality" => "Yemeni",
        );

        $countries[] = array(
            "num_code" => "894",
            "alpha_2_code" => "ZM",
            "alpha_3_code" => "ZMB",
            "en_short_name" => "Zambia",
            "nationality" => "Zambian",
        );

        $countries[] = array(
            "num_code" => "716",
            "alpha_2_code" => "ZW",
            "alpha_3_code" => "ZWE",
            "en_short_name" => "Zimbabwe",
            "nationality" => "Zimbabwean",
        );

        return $countries;
    }

    public function getCompanyIdentityTypes()
    {
        $identityTypes = array(
            array(
                'key' => 'UENLC',
                'value' => 'UEN LOCAL COMPANY'
            ),
            array(
                'key' => 'OTHERS',
                'value' => 'OTHERS'
            ),
        );

        return $identityTypes;
    }

    public function getCurrencies()
    {
        $currencies = array(
            array(
                'key' => 'SGD',
                'countryName' => 'Singapore',
                'value' => 'Singapore dollar',
                'symbol' => '&#83;&#36;'
            ),
            array(
                'key' => 'MYR',
                'countryName' => 'Malaysia',
                'value' => 'Malaysian ringgit',
                'symbol' => '&#82;&#77;'
            ),
            array(
                'key' => 'ALL',
                'countryName' => 'Albania',
                'value' => 'Albanian lek',
                'symbol' => 'L'
            ),
            array(
                'key' => 'AFN',
                'countryName' => 'Afghanistan',
                'value' => 'Afghanistan Afghani',
                'symbol' => '&#1547;'
            ),

            array(
                'key' => 'ARS',
                'countryName' => 'Argentina',
                'value' => 'Argentine Peso',
                'symbol' => '&#36;'
            ),

            array(
                'key' => 'AWG',
                'countryName' => 'Aruba',
                'value' => 'Aruban florin',
                'symbol' => '&#402;'
            ),

            array(
                'key' => 'AUD',
                'countryName' => 'Australia',
                'value' => 'Australian Dollar',
                'symbol' => '&#65;&#36;'
            ),

            array(
                'key' => 'AZN',
                'countryName' => 'Azerbaijan',
                'value' => 'Azerbaijani Manat',
                'symbol' => '&#8380;'
            ),

            array(
                'key' => 'BSD',
                'countryName' => 'The Bahamas',
                'value' => 'Bahamas Dollar',
                'symbol' => '&#66;&#36;'
            ),

            array(
                'key' => 'BBD',
                'countryName' => 'Barbados',
                'value' => 'Barbados Dollar',
                'symbol' => '&#66;&#100;&#115;&#36;'
            ),

            array(
                'key' => 'BDT',
                'countryName' => 'People\'s Republic of Bangladesh',
                'value' => 'Bangladeshi taka',
                'symbol' => '&#2547;'
            ),

            array(
                'key' => 'BYN',
                'countryName' => 'Belarus',
                'value' => 'Belarus Ruble',
                'symbol' => '&#66;&#114;'
            ),

            array(
                'key' => 'BZD',
                'countryName' => 'Belize',
                'value' => 'Belize Dollar',
                'symbol' => '&#66;&#90;&#36;'
            ),

            array(
                'key' => 'BMD',
                'countryName' => 'British Overseas Territory of Bermuda',
                'value' => 'Bermudian Dollar',
                'symbol' => '&#66;&#68;&#36;'
            ),

            array(
                'key' => 'BOP',
                'countryName' => 'Bolivia',
                'value' => 'Boliviano',
                'symbol' => '&#66;&#115;'
            ),

            array(
                'key' => 'BAM',
                'countryName' => 'Bosnia and Herzegovina',
                'value' => 'Bosnia-Herzegovina Convertible Marka',
                'symbol' => '&#75;&#77;'
            ),

            array(
                'key' => 'BWP',
                'countryName' => 'Botswana',
                'value' => 'Botswana pula',
                'symbol' => '&#80;'
            ),

            array(
                'key' => 'BGN',
                'countryName' => 'Bulgaria',
                'value' => 'Bulgarian lev',
                'symbol' => '&#1083;&#1074;'
            ),

            array(
                'key' => 'BRL',
                'countryName' => 'Brazil',
                'value' => 'Brazilian real',
                'symbol' => '&#82;&#36;'
            ),

            array(
                'key' => 'BND',
                'countryName' => 'Sultanate of Brunei',
                'value' => 'Brunei dollar',
                'symbol' => '&#66;&#36;'
            ),

            array(
                'key' => 'KHR',
                'countryName' => 'Cambodia',
                'value' => 'Cambodian riel',
                'symbol' => '&#6107;'
            ),

            array(
                'key' => 'CAD',
                'countryName' => 'Canada',
                'value' => 'Canadian dollar',
                'symbol' => '&#67;&#36;'
            ),

            array(
                'key' => 'KYD',
                'countryName' => 'Cayman Islands',
                'value' => 'Cayman Islands dollar',
                'symbol' => '&#36;'
            ),

            array(
                'key' => 'CLP',
                'countryName' => 'Chile',
                'value' => 'Chilean peso',
                'symbol' => '&#36;'
            ),

            array(
                'key' => 'CNY',
                'countryName' => 'China',
                'value' => 'Chinese Yuan Renminbi',
                'symbol' => '&#165;'
            ),

            array(
                'key' => 'COP',
                'countryName' => 'Colombia',
                'value' => 'Colombian peso',
                'symbol' => '&#36;'
            ),

            array(
                'key' => 'CRC',
                'countryName' => 'Costa Rica',
                'value' => 'Costa Rican colón',
                'symbol' => '&#8353;'
            ),

            array(
                'key' => 'HRK',
                'countryName' => 'Croatia',
                'value' => 'Croatian kuna',
                'symbol' => '&#107;&#110;'
            ),

            array(
                'key' => 'CUP',
                'countryName' => 'Cuba',
                'value' => 'Cuban peso',
                'symbol' => '&#8369;'
            ),

            array(
                'key' => 'CZK',
                'countryName' => 'Czech Republic',
                'value' => 'Czech koruna',
                'symbol' => '&#75;&#269;'
            ),

            array(
                'key' => 'DKK',
                'countryName' => 'Denmark, Greenland, and the Faroe Islands',
                'value' => 'Danish krone',
                'symbol' => '&#107;&#114;'
            ),

            array(
                'key' => 'DOP',
                'countryName' => 'Dominican Republic',
                'value' => 'Dominican peso',
                'symbol' => '&#82;&#68;&#36;'
            ),

            array(
                'key' => 'XCD',
                'countryName' => 'Antigua and Barbuda, Commonwealth of Dominica, Grenada, Montserrat, St. Kitts and Nevis, Saint Lucia and St. Vincent and the Grenadines',
                'value' => 'Eastern Caribbean dollar',
                'symbol' => '&#36;'
            ),

            array(
                'key' => 'EGP',
                'countryName' => 'Egypt',
                'value' => 'Egyptian pound',
                'symbol' => '&#163;'
            ),

            array(
                'key' => 'SVC',
                'countryName' => 'El Salvador',
                'value' => 'Salvadoran colón',
                'symbol' => '&#36;'
            ),

            array(
                'key' => 'EEK',
                'countryName' => 'Estonia',
                'value' => 'Estonian kroon',
                'symbol' => '&#75;&#114;'
            ),

            array(
                'key' => 'EUR',
                'countryName' => 'European Union, Italy, Belgium, Bulgaria, Croatia, Cyprus, Czechia, Denmark, Estonia, Finland, France, Germany, 
                    Greece, Hungary, Ireland, Latvia, Lithuania, Luxembourg, Malta, Netherlands, Poland, 
                    Portugal, Romania, Slovakia, Slovenia, Spain, Sweden',
                'value' => 'Euro',
                'symbol' => '&#8364;'
            ),

            array(
                'key' => 'FKP',
                'countryName' => 'Falkland Islands',
                'value' => 'Falkland Islands (Malvinas) Pound',
                'symbol' => '&#70;&#75;&#163;'
            ),

            array(
                'key' => 'FJD',
                'countryName' => 'Fiji',
                'value' => 'Fijian dollar',
                'symbol' => '&#70;&#74;&#36;'
            ),

            array(
                'key' => 'GHC',
                'countryName' => 'Ghana',
                'value' => 'Ghanaian cedi',
                'symbol' => '&#71;&#72;&#162;'
            ),

            array(
                'key' => 'GIP',
                'countryName' => 'Gibraltar',
                'value' => 'Gibraltar pound',
                'symbol' => '&#163;'
            ),

            array(
                'key' => 'GTQ',
                'countryName' => 'Guatemala',
                'value' => 'Guatemalan quetzal',
                'symbol' => '&#81;'
            ),

            array(
                'key' => 'GGP',
                'countryName' => 'Guernsey',
                'value' => 'Guernsey pound',
                'symbol' => '&#81;'
            ),

            array(
                'key' => 'GYD',
                'countryName' => 'Guyana',
                'value' => 'Guyanese dollar',
                'symbol' => '&#71;&#89;&#36;'
            ),

            array(
                'key' => 'HNL',
                'countryName' => 'Honduras',
                'value' => 'Honduran lempira',
                'symbol' => '&#76;'
            ),

            array(
                'key' => 'HKD',
                'countryName' => 'Hong Kong',
                'value' => 'Hong Kong dollar',
                'symbol' => '&#72;&#75;&#36;'
            ),

            array(
                'key' => 'HUF',
                'countryName' => 'Hungary',
                'value' => 'Hungarian forint',
                'symbol' => '&#70;&#116;'
            ),

            array(
                'key' => 'ISK',
                'countryName' => 'Iceland',
                'value' => 'Icelandic króna',
                'symbol' => '&#237;&#107;&#114;'
            ),

            array(
                'key' => 'INR',
                'countryName' => 'India',
                'value' => 'Indian rupee',
                'symbol' => '&#8377;'
            ),

            array(
                'key' => 'IDR',
                'countryName' => 'Indonesia',
                'value' => 'Indonesian rupiah',
                'symbol' => '&#82;&#112;'
            ),

            array(
                'key' => 'IRR',
                'countryName' => 'Iran',
                'value' => 'Iranian rial',
                'symbol' => '&#65020;'
            ),

            array(
                'key' => 'IMP',
                'countryName' => 'Isle of Man',
                'value' => 'Manx pound',
                'symbol' => '&#163;'
            ),

            array(
                'key' => 'ILS',
                'countryName' => 'Israel, Palestinian territories of the West Bank and the Gaza Strip',
                'value' => 'Israeli Shekel',
                'symbol' => '&#8362;'
            ),

            array(
                'key' => 'JMD',
                'countryName' => 'Jamaica',
                'value' => 'Jamaican dollar',
                'symbol' => '&#74;&#36;'
            ),

            array(
                'key' => 'JPY',
                'countryName' => 'Japan',
                'value' => 'Japanese yen',
                'symbol' => '&#165;'
            ),

            array(
                'key' => 'JEP',
                'countryName' => 'Jersey',
                'value' => 'Jersey pound',
                'symbol' => '&#163;'
            ),

            array(
                'key' => 'KZT',
                'countryName' => 'Kazakhstan',
                'value' => 'Kazakhstani tenge',
                'symbol' => '&#8376;'
            ),

            array(
                'key' => 'KPW',
                'countryName' => 'North Korea',
                'value' => 'North Korean won',
                'symbol' => '&#8361;'
            ),

            array(
                'key' => 'KPW',
                'countryName' => 'South Korea',
                'value' => 'South Korean won',
                'symbol' => '&#8361;'
            ),

            array(
                'key' => 'KGS',
                'countryName' => 'Kyrgyz Republic',
                'value' => 'Kyrgyzstani som',
                'symbol' => '&#1083;&#1074;'
            ),

            array(
                'key' => 'LAK',
                'countryName' => 'Laos',
                'value' => 'Lao kip',
                'symbol' => '&#8365;'
            ),

            array(
                'key' => 'LAK',
                'countryName' => 'Laos',
                'value' => 'Latvian lats',
                'symbol' => '&#8364;'
            ),

            array(
                'key' => 'LVL',
                'countryName' => 'Laos',
                'value' => 'Latvian lats',
                'symbol' => '&#8364;'
            ),

            array(
                'key' => 'LBP',
                'countryName' => 'Lebanon',
                'value' => 'Lebanese pound',
                'symbol' => '&#76;&#163;'
            ),

            array(
                'key' => 'LRD',
                'countryName' => 'Liberia',
                'value' => 'Liberian dollar',
                'symbol' => '&#76;&#68;&#36;'
            ),

            array(
                'key' => 'LTL',
                'countryName' => 'Lithuania',
                'value' => 'Lithuanian litas',
                'symbol' => '&#8364;'
            ),

            array(
                'key' => 'MKD',
                'countryName' => 'North Macedonia',
                'value' => 'Macedonian denar',
                'symbol' => '&#1076;&#1077;&#1085;'
            ),

            array(
                'key' => 'MUR',
                'countryName' => 'Mauritius',
                'value' => 'Mauritian rupee',
                'symbol' => '&#82;&#115;'
            ),

            array(
                'key' => 'MXN',
                'countryName' => 'Mexico',
                'value' => 'Mexican peso',
                'symbol' => '&#77;&#101;&#120;&#36;'
            ),

            array(
                'key' => 'MNT',
                'countryName' => 'Mongolia',
                'value' => 'Mongolian tögrög',
                'symbol' => '&#8366;'
            ),


            array(
                'key' => 'MZN',
                'countryName' => 'Mozambique',
                'value' => 'Mozambican metical',
                'symbol' => '&#77;&#84;'
            ),

            array(
                'key' => 'NAD',
                'countryName' => 'Namibia',
                'value' => 'Namibian dollar',
                'symbol' => '&#78;&#36;'
            ),

            array(
                'key' => 'NPR',
                'countryName' => 'Federal Democratic Republic of Nepal',
                'value' => 'Nepalese rupee',
                'symbol' => '&#82;&#115;&#46;'
            ),

            array(
                'key' => 'ANG',
                'countryName' => 'Curaçao and Sint Maarten',
                'value' => 'Netherlands Antillean guilder',
                'symbol' => '&#402;'
            ),

            array(
                'key' => 'NZD',
                'countryName' => 'New Zealand, the Cook Islands, Niue, the Ross Dependency, Tokelau, the Pitcairn Islands',
                'value' => 'New Zealand dollar',
                'symbol' => '&#36;'
            ),


            array(
                'key' => 'NIO',
                'countryName' => 'Nicaragua',
                'value' => 'Nicaraguan córdoba',
                'symbol' => '&#67;&#36;'
            ),

            array(
                'key' => 'NGN',
                'countryName' => 'Nigeria',
                'value' => 'Nigerian naira',
                'symbol' => '&#8358;'
            ),

            array(
                'key' => 'NOK',
                'countryName' => 'Norway and its dependent territories',
                'value' => 'Norwegian krone',
                'symbol' => '&#107;&#114;'
            ),

            array(
                'key' => 'OMR',
                'countryName' => 'Oman',
                'value' => 'Omani rial',
                'symbol' => '&#65020;'
            ),

            array(
                'key' => 'PKR',
                'countryName' => 'Pakistan',
                'value' => 'Pakistani rupee',
                'symbol' => '&#82;&#115;'
            ),

            array(
                'key' => 'PAB',
                'countryName' => 'Panama',
                'value' => 'Panamanian balboa',
                'symbol' => '&#66;&#47;&#46;'
            ),

            array(
                'key' => 'PYG',
                'countryName' => 'Paraguay',
                'value' => 'Paraguayan Guaraní',
                'symbol' => '&#8370;'
            ),

            array(
                'key' => 'PEN',
                'countryName' => 'Peru',
                'value' => 'Sol',
                'symbol' => '&#83;&#47;&#46;'
            ),

            array(
                'key' => 'PHP',
                'countryName' => 'Philippines',
                'value' => 'Philippine peso',
                'symbol' => '&#8369;'
            ),

            array(
                'key' => 'PLN',
                'countryName' => 'Poland',
                'value' => 'Polish złoty',
                'symbol' => '&#122;&#322;'
            ),

            array(
                'key' => 'QAR',
                'countryName' => 'State of Qatar',
                'value' => 'Qatari Riyal',
                'symbol' => '&#65020;'
            ),

            array(
                'key' => 'RON',
                'countryName' => 'Romania',
                'value' => 'Romanian leu (Leu românesc)',
                'symbol' => '&#76;'
            ),

            array(
                'key' => 'RUB',
                'countryName' => 'Russian Federation, Abkhazia and South Ossetia, Donetsk and Luhansk',
                'value' => 'Russian ruble',
                'symbol' => '&#8381;'
            ),


            array(
                'key' => 'SHP',
                'countryName' => 'Saint Helena, Ascension and Tristan da Cunha',
                'value' => 'Saint Helena pound',
                'symbol' => '&#163;'
            ),

            array(
                'key' => 'SAR',
                'countryName' => 'Saudi Arabia',
                'value' => 'Saudi riyal',
                'symbol' => '&#65020;'
            ),

            array(
                'key' => 'RSD',
                'countryName' => 'Serbia',
                'value' => 'Serbian dinar',
                'symbol' => '&#100;&#105;&#110;'
            ),

            array(
                'key' => 'SCR',
                'countryName' => 'Seychelles',
                'value' => 'Seychellois rupee',
                'symbol' => '&#82;&#115;'
            ),

            array(
                'key' => 'SBD',
                'countryName' => 'Solomon Islands',
                'value' => 'Solomon Islands dollar',
                'symbol' => '&#83;&#73;&#36;'
            ),

            array(
                'key' => 'SOS',
                'countryName' => 'Somalia',
                'value' => 'Somali shilling',
                'symbol' => '&#83;&#104;&#46;&#83;&#111;'
            ),

            array(
                'key' => 'ZAR',
                'countryName' => 'South Africa',
                'value' => 'South African rand',
                'symbol' => '&#82;'
            ),

            array(
                'key' => 'LKR',
                'countryName' => 'Sri Lanka',
                'value' => 'Sri Lankan rupee',
                'symbol' => '&#82;&#115;'
            ),


            array(
                'key' => 'SEK',
                'countryName' => 'Sweden',
                'value' => 'Swedish krona',
                'symbol' => '&#107;&#114;'
            ),


            array(
                'key' => 'CHF',
                'countryName' => 'Switzerland',
                'value' => 'Swiss franc',
                'symbol' => '&#67;&#72;&#102;'
            ),

            array(
                'key' => 'SRD',
                'countryName' => 'Suriname',
                'value' => 'Suriname Dollar',
                'symbol' => '&#83;&#114;&#36;'
            ),

            array(
                'key' => 'SYP',
                'countryName' => 'Syria',
                'value' => 'Syrian pound',
                'symbol' => '&#163;&#83;'
            ),

            array(
                'key' => 'TWD',
                'countryName' => 'Taiwan',
                'value' => 'New Taiwan dollar',
                'symbol' => '&#78;&#84;&#36;'
            ),


            array(
                'key' => 'THB',
                'countryName' => 'Thailand',
                'value' => 'Thai baht',
                'symbol' => '&#3647;'
            ),


            array(
                'key' => 'TTD',
                'countryName' => 'Trinidad and Tobago',
                'value' => 'Trinidad and Tobago dollar',
                'symbol' => '&#84;&#84;&#36;'
            ),


            array(
                'key' => 'TRY',
                'countryName' => 'Turkey, Turkish Republic of Northern Cyprus',
                'value' => 'Turkey Lira',
                'symbol' => '&#8378;'
            ),

            array(
                'key' => 'TVD',
                'countryName' => 'Tuvalu',
                'value' => 'Tuvaluan dollar',
                'symbol' => '&#84;&#86;&#36;'
            ),

            array(
                'key' => 'UAH',
                'countryName' => 'Ukraine',
                'value' => 'Ukrainian hryvnia',
                'symbol' => '&#8372;'
            ),


            array(
                'key' => 'GBP',
                'countryName' => 'United Kingdom, Jersey, Guernsey, the Isle of Man, Gibraltar, South Georgia and the South Sandwich Islands, the British Antarctic Territory, and Tristan da Cunha',
                'value' => 'Pound sterling',
                'symbol' => '&#163;'
            ),


            array(
                'key' => 'UGX',
                'countryName' => 'Uganda',
                'value' => 'Ugandan shilling',
                'symbol' => '&#85;&#83;&#104;'
            ),


            array(
                'key' => 'USD',
                'countryName' => 'United States',
                'value' => 'United States dollar',
                'symbol' => '&#36;'
            ),

            array(
                'key' => 'UYU',
                'countryName' => 'Uruguayan',
                'value' => 'Peso Uruguayolar',
                'symbol' => '&#36;&#85;'
            ),

            array(
                'key' => 'UZS',
                'countryName' => 'Uzbekistan',
                'value' => 'Uzbekistani soʻm',
                'symbol' => '&#1083;&#1074;'
            ),


            array(
                'key' => 'VEF',
                'countryName' => 'Venezuela',
                'value' => 'Venezuelan bolívar',
                'symbol' => '&#66;&#115;'
            ),


            array(
                'key' => 'VND',
                'countryName' => 'Vietnam',
                'value' => 'Vietnamese dong (Đồng)',
                'symbol' => '&#8363;'
            ),

            array(
                'key' => 'VND',
                'countryName' => 'Yemen',
                'value' => 'Yemeni rial',
                'symbol' => '&#65020;'
            ),

            array(
                'key' => 'ZWD',
                'countryName' => 'Zimbabwe',
                'value' => 'Zimbabwean dollar',
                'symbol' => '&#90;&#36;'
            ),
        );

        return $currencies;
    }

    public function getGenders()
    {
        $genders = [];

        $genders[] = [
            'key' => 'Male',
            'value' => 'Male'
        ];

        $genders[] = [
            'key' => 'Female',
            'value' => 'Female'
        ];

        $genders[] = [
            'key' => 'Others',
            'value' => 'Others'
        ];

        return $genders;
    }

    public function getIdentityTypes()
    {
        $identityTypes = [];

        $identityTypes[] = [
            'key' => 'NRIC',
            'value' => 'NRIC'
        ];

        $identityTypes[] = [
            'key' => 'FIN',
            'value' => 'FIN'
        ];

        $identityTypes[] = [
            'key' => 'PASSPORT',
            'value' => 'Passport'
        ];

        $identityTypes[] = [
            'key' => 'OTHERS',
            'value' => 'Others'
        ];

        return $identityTypes;
    }

    public function getRaces()
    {
        $races = [];

        $races[] = [
            'key' => 'Chinese',
            'value' => 'Chinese',
        ];

        $races[] = [
            'key' => 'Malay',
            'value' => 'Malay',
        ];

        $races[] = [
            'key' => 'Indian',
            'value' => 'Indian',
        ];

        $races[] = [
            'key' => 'Eurasian',
            'value' => 'Eurasian',
        ];

        $races[] = [
            'key' => 'Others',
            'value' => 'Others',
        ];

        return $races;
    }

    public function getResidencyStatuses()
    {
        $statuses = [];

        $statuses[] = [
            'key' => 'SG',
            'value' => 'Singaporean',
        ];

        $statuses[] = [
            'key' => 'PR',
            'value' => 'Permanent Resident',
        ];

        $statuses[] = [
            'key' => 'EP',
            'value' => 'Employment Pass',
        ];

        $statuses[] = [
            'key' => 'WP',
            'value' => 'Work Permit',
        ];

        $statuses[] = [
            'key' => 'O',
            'value' => 'Others',
        ];


        return $statuses;
    }

    public function getFrontendUtcDateTimeFormat()
    {
        return 'Y-m-d\TH:i:s.u\Z';
    }

    public function getTimezone()
    {
        return 'Asia/Singapore';
    }

    public function getSqlDateTimeFormat()
    {
        return 'Y-m-d H:i:s';
    }
}
