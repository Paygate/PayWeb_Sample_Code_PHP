<?php
/*
 * Copyright (c) 2021 PayGate (Pty) Ltd
 *
 * Author: App Inlet (Pty) Ltd
 *
 * Released under the GNU General Public License
 */

	/**
	 * Gets the current URL of the web server running the sample code
	 *
	 * @return array
	 */
	function getCurrentUrl() {

		$url = array();

		// set protocol
		$url['protocol'] = 'http://';
		if (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) === 'on' || $_SERVER['HTTPS'] == 1)) {
			$url['protocol'] = 'https://';
		} elseif (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
			$url['protocol'] = 'https://';
		}

		// set host
		$url['host'] = $_SERVER['HTTP_HOST'];
		// set request uri in a secure way
		$url['request_uri'] = $_SERVER['REQUEST_URI'];

		return $url;
	}

	/**
	 * Gets the root directory of the Sample Code
	 *
	 * @param $urlArray
	 * @return string
	 */
	function getRoot($urlArray){

		$pathArray = explode('/', $urlArray['request_uri']);

		return $pathArray[1];
	}

	/**
	 * @param $urlArray
	 * @return string
	 */
	function getFinalDirectory($urlArray){

		$pathArray      = explode('/', $urlArray['request_uri']);
		$numDirectories = count($pathArray);
		$finalDirectory = '';

		for($i=0;$i<($numDirectories-1);$i++){

			$finalDirectory .= $pathArray[$i].'/';
		}

		return $finalDirectory;
	}

	/**
	 * generates a string reference according to Date/Time
	 *
	 * @return string
	 */
	function generateReference(){
		return 'pgtest_' . getDateTime('YmdHis');
	}

	function getDateTime($format){
		$dateTime = new DateTime();
		return $dateTime->format($format);
	}

	function generateCountrySelectOptions(){
		$options = '';
		$country = 'ZAF';

		$mostUsedCountryArray = array(
			'DEU' => 'Germany',
			'ZAF' => 'South Africa',
			'USA' => 'United States'
		);

		$countryArray = array(
			'ARG'  => 'Argentina',
			'BRA'  => 'Brazil',
			'CHL'  => 'Chile',
			'KEN'  => 'Kenya',
			'MEX'  => 'Mexico',
			'GBR'  => 'United Kingdom',
			'USA'  => 'United States',
			'ZAF'  => 'South Africa',
			'AFG'  => 'Afghanistan',
			'ALB'  => 'Albania',
			'DZA'  => 'Algeria',
			'ASM'  => 'American Samoa',
			'AND'  => 'Andorra',
			'AGO'  => 'Angola',
			'AIA'  => 'Anguilla',
			'ATA'  => 'Antarctica',
			'ATG'  => 'Antigua and Barbuda',
			'ARG'  => 'Argentina',
			'ARM'  => 'Armenia',
			'ABW'  => 'Aruba',
			'AUS'  => 'Australia',
			'AUT'  => 'Austria',
			'AZE'  => 'Azerbaijan',
			'BHS'  => 'Bahamas',
			'BHR'  => 'Bahrain',
			'BGD'  => 'Bangladesh',
			'BRB'  => 'Barbados',
			'BLR'  => 'Belarus',
			'BEL'  => 'Belgium',
			'BLZ'  => 'Belize',
			'BEN'  => 'Benin',
			'BMU'  => 'Bermuda',
			'BTN'  => 'Bhutan',
			'BOL'  => 'Bolivia',
			'BIH'  => 'Bosnia and Herzegovina',
			'BWA'  => 'Botswana',
			'BVT'  => 'Bouvet Island',
			'BRA'  => 'Brazil',
			'IOT'  => 'British Indian Ocean Territory',
			'VGB'  => 'British Virgin Islands',
			'BRN'  => 'Brunei Darussalam',
			'BGR'  => 'Bulgaria',
			'BFA'  => 'Burkina Faso',
			'BDI'  => 'Burundi',
			'KHM'  => 'Cambodia',
			'CMR'  => 'Cameroon',
			'CAN'  => 'Canada',
			'CPV'  => 'Cape Verde',
			'CYM'  => 'Cayman Islands',
			'CAF'  => 'Central African Republic',
			'TCD'  => 'Chad',
			'CHL'  => 'Chile',
			'CHN'  => 'China',
			'CXR'  => 'Christmas Island',
			'CCK'  => 'Cocos (Keeling) Islands',
			'COL'  => 'Colombia',
			'COL'  => 'Comoros',
			'COG'  => 'Congo',
			'COD'  => 'Congo, The Democratic Republic of The',
			'COK'  => 'Cook Islands',
			'CRI'  => 'Costa Rica',
			'CIV'  => 'Cote D\'ivoire',
			'CHRV' => 'Croatia',
			'CUB'  => 'Cuba',
			'CYP'  => 'Cyprus',
			'CZE'  => 'Czech Republic',
			'DNK'  => 'Denmark',
			'DJI'  => 'Djibouti',
			'DMA'  => 'Dominica',
			'DOM'  => 'Dominican Republic',
			'ECU'  => 'Ecuador',
			'EGY'  => 'Egypt',
			'SLV'  => 'El Salvador',
			'GNQ'  => 'Equatorial Guinea',
			'ERI'  => 'Eritrea',
			'EST'  => 'Estonia',
			'ETH'  => 'Ethiopia',
			'FLK'  => 'Falkland Islands (Malvinas)',
			'FRO'  => 'Faroe Islands',
			'FJI'  => 'Fiji',
			'FIN'  => 'Finland',
			'FRA'  => 'France',
			'FXX'  => 'French Metropolitan',
			'GUF'  => 'French Guiana',
			'PYF'  => 'French Polynesia',
			'ATF'  => 'French Southern Territories',
			'GAB'  => 'Gabon',
			'GMB'  => 'Gambia',
			'GEO'  => 'Georgia',
			'DEU'  => 'Germany',
			'GHA'  => 'Ghana',
			'GIB'  => 'Gibraltar',
			'GRC'  => 'Greece',
			'GRL'  => 'Greenland',
			'GRD'  => 'Grenada',
			'GLP'  => 'Guadeloupe',
			'GUM'  => 'Guam',
			'GTM'  => 'Guatemala',
			'GIN'  => 'Guinea',
			'GNB'  => 'Guinea-bissau',
			'GUY'  => 'Guyana',
			'HTI'  => 'Haiti',
			'HMD'  => 'Heard Island and Mcdonald Islands',
			'VAT'  => 'Holy See (Vatican City State)',
			'HND'  => 'Honduras',
			'HKG'  => 'Hong Kong',
			'HUN'  => 'Hungary',
			'ISL'  => 'Iceland',
			'IND'  => 'India',
			'IDN'  => 'Indonesia',
			'IRN'  => 'Iran, Islamic Republic of',
			'IRQ'  => 'Iraq',
			'IRL'  => 'Ireland',
			'ISR'  => 'Israel',
			'ITA'  => 'Italy',
			'JAM'  => 'Jamaica',
			'JPN'  => 'Japan',
			'JOR'  => 'Jordan',
			'KAZ'  => 'Kazakhstan',
			'KEN'  => 'Kenya',
			'KIR'  => 'Kiribati',
			'PRK'  => 'Korea, Democratic People\'s Republic of',
			'KOR'  => 'Korea, Republic of',
			'KWT'  => 'Kuwait',
			'KGZ'  => 'Kyrgyzstan',
			'LAO'  => 'Lao People\'s Democratic Republic',
			'LVA'  => 'Latvia',
			'LBN'  => 'Lebanon',
			'LSO'  => 'Lesotho',
			'LBR'  => 'Liberia',
			'LBY'  => 'Libyan Arab Jamahiriya',
			'LIE'  => 'Liechtenstein',
			'LTU'  => 'Lithuania',
			'LUX'  => 'Luxembourg',
			'MAC'  => 'Macau China',
			'MKD'  => 'Macedonia, The Former Yugoslav Republic of',
			'MDG'  => 'Madagascar',
			'MWI'  => 'Malawi',
			'MYS'  => 'Malaysia',
			'MDV'  => 'Maldives',
			'MLI'  => 'Mali',
			'MLT'  => 'Malta',
			'MHL'  => 'Marshall Islands',
			'MTQ'  => 'Martinique',
			'MRT'  => 'Mauritania',
			'MUS'  => 'Mauritius',
			'MYT'  => 'Mayotte',
			'MEX'  => 'Mexico',
			'FSM'  => 'Micronesia, Federated States of',
			'MDA'  => 'Moldova, Republic of',
			'MCO'  => 'Monaco',
			'MNG'  => 'Mongolia',
			'MSR'  => 'Montserrat',
			'MAR'  => 'Morocco',
			'MOZ'  => 'Mozambique',
			'MMR'  => 'Myanmar',
			'NAM'  => 'Namibia',
			'NRU'  => 'Nauru',
			'NPL'  => 'Nepal',
			'NLD'  => 'Netherlands',
			'ANT'  => 'Netherlands Antilles',
			'NCL'  => 'New Caledonia',
			'NZL'  => 'New Zealand',
			'NIC'  => 'Nicaragua',
			'NER'  => 'Niger',
			'NGA'  => 'Nigeria',
			'NIU'  => 'Niue',
			'NFK'  => 'Norfolk Island',
			'MNP'  => 'Northern Mariana Islands',
			'NOR'  => 'Norway',
			'OMN'  => 'Oman',
			'PAK'  => 'Pakistan',
			'PLW'  => 'Palau',
			'PAN'  => 'Panama',
			'PNG'  => 'Papua New Guinea',
			'PRY'  => 'Paraguay',
			'PER'  => 'Peru',
			'PHL'  => 'Philippines',
			'PCN'  => 'Pitcairn',
			'POL'  => 'Poland',
			'PRT'  => 'Portugal',
			'PRI'  => 'Puerto Rico',
			'QAT'  => 'Qatar',
			'REU'  => 'Reunion',
			'ROM'  => 'Romania',
			'RUS'  => 'Russian Federation',
			'RWA'  => 'Rwanda',
			'SHN'  => 'Saint Helena',
			'KNA'  => 'Saint Kitts and Nevis',
			'LCA'  => 'Saint Lucia',
			'SPM'  => 'Saint Pierre and Miquelon',
			'VCT'  => 'Saint Vincent and The Grenadines',
			'WSM'  => 'Samoa',
			'SMR'  => 'San Marino',
			'STP'  => 'Sao Tome and Principe',
			'SAU'  => 'Saudi Arabia',
			'SEN'  => 'Senegal',
			'SYC'  => 'Seychelles',
			'SLE'  => 'Sierra Leone',
			'SGP'  => 'Singapore',
			'SVK'  => 'Slovakia',
			'SVN'  => 'Slovenia',
			'SLB'  => 'Solomon Islands',
			'SOM'  => 'Somalia',
			'ZAF'  => 'South Africa',
			'SGS'  => 'South Georgia and The South Sandwich Islands',
			'ESP'  => 'Spain',
			'LKA'  => 'Sri Lanka',
			'SDN'  => 'Sudan',
			'SUR'  => 'Suriname',
			'SJM'  => 'Svalbard and Jan Mayen',
			'SWZ'  => 'Swaziland',
			'SWE'  => 'Sweden',
			'CHE'  => 'Switzerland',
			'SYR'  => 'Syrian Arab Republic',
			'TWN'  => 'Taiwan, Province of China',
			'TJK'  => 'Tajikistan',
			'TZA'  => 'Tanzania, United Republic of',
			'THA'  => 'Thailand',
			'TGO'  => 'Togo',
			'TKL'  => 'Tokelau',
			'TON'  => 'Tonga',
			'TTO'  => 'Trinidad and Tobago',
			'TUN'  => 'Tunisia',
			'TUR'  => 'Turkey',
			'TKM'  => 'Turkmenistan',
			'TCA'  => 'Turks and Caicos Islands',
			'TUV'  => 'Tuvalu',
			'UGA'  => 'Uganda',
			'UKR'  => 'Ukraine',
			'ARE'  => 'United Arab Emirates',
			'GBR'  => 'United Kingdom',
			'USA'  => 'United States',
			'UMI'  => 'United States Minor Outlying Islands',
			'VIR'  => 'U.S. Virgin Islands',
			'URY'  => 'Uruguay',
			'UZB'  => 'Uzbekistan',
			'VUT'  => 'Vanuatu',
			'VEN'  => 'Venezuela',
			'VNM'  => 'Vietnam',
			'WLF'  => 'Wallis and Futuna',
			'ESH'  => 'Western Sahara',
			'YEM'  => 'Yemen',
			'YUG'  => 'Yugoslavia',
			'ZMB'  => 'Zambia',
			'ZWE'  => 'Zimbabwe'
		);

		$defaultSet = false;

		$options .= '<optgroup label="">
    <option value="" >Select Country</option>
</optgroup>';

		$options .= '<optgroup label="Most Used">';
		foreach ($mostUsedCountryArray as $id => $name) {
			$options .= '   <option value="'.$id.'" ';
			if ($country == $id && !$defaultSet) {
				$options .= 'selected="selected" ';
				$defaultSet = true;
			}
			$options .= '>'.$name.'</option>';
		}

		$options .= '</optgroup>';
		$options .= '<optgroup label="All Countries">';

		foreach ( $countryArray as $id => $name) {

			$options .= '   <option value="'.$id.'" ';
			if ($country == $id && !$defaultSet) {
				$options .= 'selected="selected" ';
				$defaultSet = true;
			}
			$options .= '>'.$name.'</option>';
		}

		$options .= '</optgroup>';

		return $options;
	}

	$fullPath  = getCurrentUrl();
	$root      = getRoot($fullPath);
	$directory = getFinalDirectory($fullPath);