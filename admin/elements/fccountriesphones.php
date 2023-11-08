<?php
/**
 * @version 1.5 stable $Id: filters.php 1829 2014-01-05 22:18:17Z ggppdk $
 * @package Joomla
 * @subpackage FLEXIcontent
 * @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
 * @license GNU/GPL v2
 *
 * FLEXIcontent is a derivative work of the excellent QuickFAQ component
 * @copyright (C) 2008 Christoph Lukes
 * see www.schlu.net for more information
 *
 * FLEXIcontent is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('cms.html.html');      // JHtml
jimport('cms.html.select');    // \Joomla\CMS\HTML\Helpers\Select

jimport('joomla.form.helper'); // \Joomla\CMS\Form\FormHelper
\Joomla\CMS\Form\FormHelper::loadFieldClass('list');   // \Joomla\CMS\Form\Field\ListField

require_once("fcsortablelist.php");


/**
 * Renders a filter element
 *
 * @package 	Joomla
 * @subpackage	FLEXIcontent
 * @since		1.5
 */
class JFormFieldFcCountriesPhones extends \Joomla\CMS\Form\FormFieldFcSortableList
{
	/**
	 * \Joomla\CMS\Form\FormField type
	 * @access	protected
	 * @var		string
	 */

	protected $type = 'FcCountriesPhones';

	// Record list
	protected static $records = array(
		'+93'=>'+93 Afghanistan',
		'+358 18'=>'+358 18 &Aring;land Islands',
		'+355'=>'+355 Albania',
		'+213'=>'+213 Algeria',
		'+1 684'=>'+1 684 American Samoa',
		'+376'=>'+376 Andorra',
		'+244'=>'+244 Angola',
		'+1 264'=>'+1 264 Anguilla',
		'+1 268'=>'+1 268 Antigua and Barbuda',
		'+54'=>'+54 Argentina',
		'+374'=>'+374 Armenia',
		'+297'=>'+297 Aruba',
		'+61'=>'+61 Australia',
		'+47'=>'+47 Austria',
		'+994'=>'+994 Azerbaijan',
		'+1 242'=>'+1 242 Bahamas',
		'+973'=>'+973 Bahrain',
		'+880'=>'+880 Bangladesh',
		'+1 246'=>'+1 246 Barbados',
		'+375'=>'+375 Belarus',
		'+32'=>'+32 Belgium',
		'+501'=>'+501 Belize',
		'+229'=>'+229 Benin',
		'+1 441'=>'+1 441 Bermuda',
		'+975'=>'+975 Bhutan',
		'+591'=>'+591 Bolivia, Plurinational State of',
		'+599 7'=>'+599 7 Bonaire, Sint Eustatius and Saba',
		'+387'=>'+387 Bosnia and Herzegovina',
		'+267'=>'+267 Botswana',
		'+55'=>'+55 Brazil',
		'+1 284'=>'+1 284 British Indian Ocean Territory',
		'+673'=>'+673 Brunei Darussalam',
		'+359'=>'+359 Bulgaria',
		'+226 '=>'+226 Burkina Faso',
		'+257'=>'+257 Burundi',
		'+855'=>'+855 Cambodia',
		'+237'=>'+237 Cameroon',
		'+1'=>'+1 Canada',
		'+1 345'=>'+1 345 Cayman Islands',
		'+236'=>'+236 Central African Republic',
		'+235'=>'+235 Chad',
		'+56'=>'+56 Chile',
		'+86'=>'+86 China',
		'+61 89164'=>'+61 89164 Christmas Island',
		'+61 89162'=>'+61 89162 Cocos (Keeling) Islands',
		'+57'=>'+57 Colombia',
		'+269'=>'+269 Comoros',
		'+242'=>'+242 Congo',
		'+243'=>'+243 Congo, the Democratic Republic of the',
		'+682'=>'+682 Cook Islands',
		'+506'=>'+506 Costa Rica',
		'+225'=>'+225 C&ocirc;te d\'Ivoire',
		'+385'=>'+385 Croatia',
		'+53'=>'+53 Cuba',
		'+599 9'=>'+599 9 Cura&ccedil;ao',
		'+357'=>'+357 Cyprus',
		'+420'=>'+420 Czech Republic',
		'+45'=>'+45 Denmark',
		'+253'=>'+253 Djibouti',
		'+1 767'=>'+1 767 Dominica',
		'+1 809'=>'+1 809 Dominican Republic',
		'+593'=>'+593 Ecuador',
		'+20'=>'+20 Egypt',
		'+503'=>'+503 El Salvador',
		'+240'=>'+240	Equatorial Guinea',
		'+291'=>'+291 Eritrea',
		'+372'=>'+372 Estonia',
		'+251'=>'+251 Ethiopia',
		'+500'=>'+500 Falkland Islands (Malvinas)',
		'FO'=>'+298	Faroe Islands',
		'+679'=>'+679 Fiji',
		'+358'=>'+358 Finland',
		'+33'=>'+33 France',
		'+594'=>'+594 French Guiana',
		'+689'=>'+689 French Polynesia',
		'+241'=>'+241 Gabon',
		'+220'=>'+220 Gambia',
		'+995'=>'+995 Georgia',
		'+49'=>'+49 Germany',
		'+233'=>'+233 Ghana',
		'+350'=>'+350 Gibraltar',
		'+30'=>'+30 Greece',
		'+299'=>'+299 Greenland',
		'+1 473'=>'+1 473 Grenada',
		'+590'=>'+590 Guadeloupe',
		'+1 671'=>'+1 671 Guam',
		'+502'=>'+502	Guatemala',
		'+44 1481'=>'+44 1481 Guernsey',
		'+224'=>'+224 Guinea',
		'+245'=>'+245 Guinea-Bissau',
		'+592'=>'+592 Guyana',
		'+509'=>'+509 Haiti',
		'+504'=>'+504 Honduras',
		'+852'=>'+852 Hong Kong',
		'+36'=>'+36 Hungary',
		'+354'=>'+354 Iceland',
		'+91'=>'+91 India',
		'+62'=>'+62 Indonesia',
		'+98'=>'+98 Iran, Islamic Republic of',
		'+964'=>'+964 Iraq',
		'+353'=>'+353 Ireland',
		'+44 1624'=>'+44 1624 Isle of Man',
		'+972'=>'+972 Israel',
		'+39'=>'+39 Italy',
		'+1 876'=>'+1 876 Jamaica',
		'+81'=>'+81 Japan',
		'+44 1534'=>'+44 1534 Jersey',
		'+962'=>'+962 Jordan',
		'+7 6'=>'+7 6 Kazakhstan',
		'+254'=>'+254 Kenya',
		'+686'=>'+686 Kiribati',
		'+850'=>'+850 Korea, Democratic People\'s Republic of',
		'+82'=>'+82 Korea, Republic of',
		'+965'=>'+965 Kuwait',
		'+996'=>'+996 Kyrgyzstan',
		'+856'=>'+856 Lao People\'s Democratic Republic',
		'+371'=>'+371 Latvia',
		'+961'=>'+961 Lebanon',
		'+266'=>'+266 Lesotho',
		'+231'=>'+231 Liberia',
		'+218'=>'+218 Libya',
		'+423'=>'+423 Liechtenstein',
		'+370'=>'+370 Lithuania',
		'+352'=>'+352 Luxembourg',
		'+853'=>'+853 Macao',
		'+261'=>'+261 Madagascar',
		'+265'=>'+265 Malawi',
		'+60'=>'+60 Malaysia',
		'+960'=>'+960 Maldives',
		'+223'=>'+223 Mali',
		'+356'=>'+356 Malta',
		'+692'=>'+692 Marshall Islands',
		'+596'=>'+596 Martinique',
		'+222'=>'+222 Mauritania',
		'+230'=>'+230 Mauritius',
		'+262 269'=>'+262 269 Mayotte',
		'+52'=>'+52 Mexico',
		'+691'=>'+691 Micronesia, Federated States of',
		'+373'=>'+373 Moldova, Republic of',
		'+377'=>'+377 Monaco',
		'+976'=>'+976 Mongolia',
		'+382'=>'+382 Montenegro',
		'+1 664'=>'+1 664 Montserrat',
		'+212'=>'+212 Morocco',
		'+258'=>'+258 Mozambique',
		'+95'=>'+95 Myanmar',
		'+264'=>'+264 Namibia',
		'+674'=>'+674 Nauru',
		'+977'=>'+977 Nepal',
		'+31'=>'+31 Netherlands',
		'+687'=>'+687 New Caledonia',
		'+64'=>'+64 New Zealand',
		'+505'=>'+505 Nicaragua',
		'+227'=>'+227 Niger',
		'+234'=>'+234 Nigeria',
		'+683'=>'+683 Niue',
		'+672 3'=>'+672 3 Norfolk Island',
		'+389'=>'+389 Northern Mariana Islands',
		'+47'=>'+47 Norway',
		'+968'=>'+968 Oman',
		'+92'=>'+92 Pakistan',
		'+680'=>'+680 Palau',
		'+970'=>'+970 Palestinian Territory, Occupied',
		'+507'=>'+507 Panama',
		'+675'=>'+675 Papua New Guinea',
		'+595'=>'+595 Paraguay',
		'+51'=>'+51 Peru',
		'+63'=>'+63 Philippines',
		'+64'=>'+64 Pitcairn',
		'+48'=>'+48 Poland',
		'+351'=>'+351 Portugal',
		'+1 787'=>'+1 787 Puerto Rico',
		'+974'=>'+974 Qatar',
		'+262'=>'+262 R&eacute;union',
		'+40'=>'+40 Romania',
		'+7'=>'+7 Russian Federation',
		'+250'=>'+250 Rwanda',
		'+590'=>'+590 Saint Barth&eacute;lemy',
		'+290'=>'+290 Saint Helena, Ascension and Tristan da Cunha',
		'+1 869'=>'+1 869 Saint Kitts and Nevis',
		'+1 758'=>'+1 758 Saint Lucia',
		'+590'=>'+590 Saint Martin (French part)',
		'+508'=>'+508 Saint Pierre and Miquelon',
		'+1 784'=>'+1 784 Saint Vincent and the Grenadines',
		'+685'=>'+685 Samoa',
		'+378'=>'+378 San Marino',
		'+239'=>'+239 Sao Tome and Principe',
		'+966'=>'+966 Saudi Arabia',
		'+22'=>'+22 Senegal',
		'+381'=>'+381 Serbia',
		'+248'=>'+248 Seychelles',
		'+232'=>'+232 Sierra Leone',
		'+65'=>'+65 Singapore',
		'+1 721'=>'+1 721 Sint Maarten (Dutch part)',
		'+421'=>'+421 Slovakia',
		'+386I'=>'+386 Slovenia',
		'+386'=>'+386 Solomon Islands',
		'+252'=>'+252 Somalia',
		'+27'=>'+27 South Africa',
		'+500'=>'+500 South Georgia and the South Sandwich Islands',
		'+211'=>'+211 South Sudan',
		'+34'=>'+34 Spain',
		'+94'=>'+94 Sri Lanka',
		'+249'=>'+249 Sudan',
		'+597'=>'+597 Suriname',
		'+47 79'=>'+47 79 Svalbard and Jan Mayen',
		'+46'=>'+46 Sweden',
		'+41'=>'+41 Switzerland',
		'+963'=>'+963 Syrian Arab Republic',
		'+886'=>'+886 Taiwan',
		'+992'=>'+992 Tajikistan',
		'+255'=>'+255 Tanzania, United Republic of',
		'+66'=>'+66 Thailand',
		'+670'=>'+670Timor-Leste',
		'+228'=>'+228 Togo',
		'+690'=>'+690 Tokelau',
		'+676'=>'+676 Tonga',
		'+1 868'=>'+1 868 Trinidad and Tobago',
		'+216'=>'+216 Tunisia',
		'+90'=>'+90 Turkey',
		'+993'=>'+993 Turkmenistan',
		'+1 649'=>'+1 649 Turks and Caicos Islands',
		'+688'=>'+688 Tuvalu',
		'+256'=>'+256 Uganda',
		'+380'=>'+380 Ukraine',
		'+971'=>'+971 United Arab Emirates',
		'+44'=>'+44 United Kingdom',
		'+1'=>'+1 United States',
		'+598'=>'+598 Uruguay',
		'+998'=>'+998 Uzbekistan',
		'+678'=>'+678 Vanuatu',
		'+58'=>'+58 Venezuela, Bolivarian Republic of',
		'+84'=>'+84 Viet Nam',
		'+681'=>'+681 Wallis and Futuna',
		'+967'=>'+967 Yemen',
		'+260'=>'+260 Zambia',
		'+263'=>'+263 Zimbabwe'
	);
}
