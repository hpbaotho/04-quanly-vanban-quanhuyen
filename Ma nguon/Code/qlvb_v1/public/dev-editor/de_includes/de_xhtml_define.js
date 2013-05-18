// 25% Faster parsing!!!
var aTags = new Array();
aTags['p'] = 1;             aTags['span'] = 1;		    aTags['a'] = 1;	          aTags['div'] = 1;
aTags['b'] = 1;		          aTags['u'] = 1;	          aTags['i'] = 1;	          aTags['td'] = 1;
aTags['img'] = 1;		        aTags['table'] = 1;	      aTags['input'] = 1;	      aTags['li'] = 1;
aTags['ol'] = 1;	          aTags['script'] = 1;	    aTags['br'] = 1;	        aTags['textarea'] = 1;
aTags['strong'] = 1;		    aTags['center'] = 1;	    aTags['cite'] = 1;	      aTags['code'] = 1;
aTags['col'] = 10;		      aTags['colgroup'] = 1;	  aTags['dd'] = 1;	        aTags['del'] = 1;
aTags['dir'] = 1;  		      aTags['dfn'] = 1; 	      aTags['acronym'] = 1;	    aTags['dl'] = 1;
aTags['dt'] = 1;		        aTags['em'] = 1;	        aTags['fieldset'] = 1;	  aTags['font'] = 1;
aTags['form'] = 1;		      aTags['frame'] = 1;	      aTags['frameset'] = 1;	  aTags['h1'] = 1;
aTags['h2'] = 1;		        aTags['h3'] = 1;	        aTags['h4'] = 1;	        aTags['h5'] = 1;
aTags['h6'] = 1;		        aTags['head'] = 1;	      aTags['hr'] = 1;	        aTags['html'] = 1;
aTags['area'] = 1;		      aTags['iframe'] = 1;	    aTags['base'] = 1;	      aTags['bdo'] = 1;
aTags['ins'] = 1;	  	      aTags['isindex'] = 1;	    aTags['kbd'] = 1;	        aTags['label'] = 1;
aTags['legend'] = 1;		    aTags['big'] = 1;	        aTags['link'] = 1;	      aTags['map'] = 1;
aTags['menu'] = 1;		      aTags['meta'] = 1;	      aTags['noframes'] = 1;	  aTags['noscript'] = 1;
aTags['object'] = 1;		    aTags['blockquote'] = 1;	aTags['optgroup'] = 1;	  aTags['option'] = 1;
aTags['!doctype'] = 1;      aTags['param'] = 1;	      aTags['pre'] = 1;	        aTags['q'] = 1;
aTags['s'] = 1;             aTags['samp'] = 1;	      aTags['body'] = 1;	      aTags['select'] = 1;
aTags['small'] = 1; 		    aTags['abbr'] = 1;	      aTags['strike'] = 1;	    aTags['caption'] = 1;
aTags['style'] = 1; 	      aTags['sub'] = 1;	        aTags['sup'] = 1;	        aTags['basefont'] = 1;
aTags['tbody'] = 1;	        aTags['address'] = 1;	    aTags['button'] = 1;	    aTags['tfoot'] = 1;
aTags['th'] = 1;		        aTags['thead'] = 1;	      aTags['title'] = 1;	      aTags['tr'] = 1;
aTags['tt'] = 1;		        aTags['applet'] = 1;	    aTags['ul'] = 1;	        aTags['v'] = 1;
aTags['embed'] = 1;

var aEmptyAttributes = new Array();
aEmptyAttributes['compact'] = 1;
aEmptyAttributes['nowrap'] = 1;
aEmptyAttributes['ismap'] = 1;
aEmptyAttributes['declare'] = 1;
aEmptyAttributes['noshade'] = 1;
aEmptyAttributes['checked'] = 1;
aEmptyAttributes['disabled'] = 1;
aEmptyAttributes['readonly'] = 1;
aEmptyAttributes['multiple'] = 1;
aEmptyAttributes['selected'] = 1;
aEmptyAttributes['noresize'] = 1;
aEmptyAttributes['defer'] = 1;
aEmptyAttributes['nosave'] = 1;

var aEmptyTags = new Array();
aEmptyTags['br'] = 1;
aEmptyTags['img'] = 1;
aEmptyTags['input'] = 1;
aEmptyTags['hr'] = 1;
aEmptyTags['link'] = 1;
aEmptyTags['meta'] = 1;
aEmptyTags['area'] = 1;
aEmptyTags['param'] = 1;
aEmptyTags['base'] = 1;
aEmptyTags['basefont'] = 1;

var aFlushAfter = new Array();
aFlushAfter['td'] = 1;
aFlushAfter['tr'] = 1;
aFlushAfter['table'] = 1;
aFlushAfter['ul'] = 1;
aFlushAfter['select'] = 1;
aFlushAfter['html'] = 1;
aFlushAfter['body'] = 1;

var aNoNestedTags = new Array();
aNoNestedTags['li'] = 1;
aNoNestedTags['option'] = 1;
aNoNestedTags['p'] = 1;

var aRemoveEmptyTags = new Array();
aRemoveEmptyTags[0] = "p";              aRemoveEmptyTags[1] = "span";		      aRemoveEmptyTags[2] = "div";	          aRemoveEmptyTags[3] = "b";
aRemoveEmptyTags[4] = "u";		          aRemoveEmptyTags[5] = "i";	          aRemoveEmptyTags[6] = "strong";

var aRequiredAttributes = new Array();
aRequiredAttributes['img'] = 'alt';
aRequiredAttributes['area'] = 'alt';
aRequiredAttributes['script'] = 'type';
aRequiredAttributes['style'] = 'type';

var aRequiredAttributesValues = new Array();
aRequiredAttributesValues['img'] = '';
aRequiredAttributesValues['area'] = '';
aRequiredAttributesValues['script'] = ''; //'text/javascript';
aRequiredAttributesValues['style'] = 'text/css';

var aEntities = new Array();
aEntities['&nbsp;']   = '&nbsp;'; // &nbsp; *must* remain &nbsp;
aEntities['&quot;']   = '&#34;';
aEntities['&amp;']    = '&#38;';
aEntities['&lt;']     = '&#60;';
aEntities['&gt;']     = '&#62;';
aEntities['&iexcl;']  = '&#161;';
aEntities['&cent;']   = '&#162;';
aEntities['&pound;']  = '&#163;';
aEntities['&curren;'] = '&#164;';
aEntities['&yen;']    = '&#165;';
aEntities['&brvbar;'] = '&#166;';
aEntities['&brkbar;'] = '&#166;';
aEntities['&sect;']   = '&#167;';
aEntities['&uml;']    = '&#168;';
aEntities['&die;']    = '&#168;';
aEntities['&copy;']   = '&#169;';
aEntities['&ordf;']   = '&#170;';
aEntities['&laquo;']  = '&#171;';
aEntities['&not;']    = '&#172;';
aEntities['&reg;']    = '&#174;';
aEntities['&macr;']   = '&#175;';
aEntities['&hibar;']  = '&#175;';
aEntities['&deg;']    = '&#176;';
aEntities['&plusmn;'] = '&#177;';
aEntities['&sup2;']   = '&#178;';
aEntities['&sup3;']   = '&#179;';
aEntities['&acute;'] = '&#180;';
aEntities['&micro;'] = '&#181;';
aEntities['&para;'] = '&#182;';
aEntities['&middot;'] = '&#183;';
aEntities['&cedil;'] = '&#184;';
aEntities['&sup1;'] = '&#185;';
aEntities['&ordm;'] = '&#186;';
aEntities['&raquo;'] = '&#187;';
aEntities['&frac14;'] = '&#188;';
aEntities['&frac12;'] = '&#189;';
aEntities['&frac34;'] = '&#190;';
aEntities['&iquest;'] = '&#191;';
aEntities['&Agrave;'] = '&#192;';
aEntities['&Aacute;'] = '&#193;';
aEntities['&Acirc;'] = '&#194;';
aEntities['&Atilde;'] = '&#195;';
aEntities['&Auml;'] = '&#196;';
aEntities['&Aring;'] = '&#197;';
aEntities['&AElig;'] = '&#198;';
aEntities['&Ccedil;'] = '&#199;';
aEntities['&Egrave;'] = '&#200;';
aEntities['&Eacute;'] = '&#201;';
aEntities['&Ecirc;'] = '&#202;';
aEntities['&Euml;'] = '&#203;';
aEntities['&Igrave;'] = '&#204;';
aEntities['&Iacute;'] = '&#205;';
aEntities['&Icirc;'] = '&#206;';
aEntities['&Iuml;'] = '&#207;';
aEntities['&ETH;'] = '&#208;';
aEntities['&Ntilde;'] = '&#209;';
aEntities['&Ograve;'] = '&#210;';
aEntities['&Oacute;'] = '&#211;';
aEntities['&Ocirc;'] = '&#212;';
aEntities['&Otilde;'] = '&#213;';
aEntities['&Ouml;'] = '&#214;';
aEntities['&times;'] = '&#215;';
aEntities['&Oslash;'] = '&#216;';
aEntities['&Ugrave;'] = '&#217;';
aEntities['&Uacute;'] = '&#218;';
aEntities['&Ucirc;'] = '&#219;';
aEntities['&Uuml;'] = '&#220;';
aEntities['&Yacute;'] = '&#221;';
aEntities['&THORN;'] = '&#222;';
aEntities['&szlig;'] = '&#223;';
aEntities['&agrave;'] = '&#224;';
aEntities['&aacute;'] = '&#225;';
aEntities['&acirc;'] = '&#226;';
aEntities['&atilde;'] = '&#227;';
aEntities['&auml;'] = '&#228;';
aEntities['&aring;'] = '&#229;';
aEntities['&aelig;'] = '&#230;';
aEntities['&ccedil;'] = '&#231;';
aEntities['&egrave;'] = '&#232;';
aEntities['&eacute;'] = '&#233;';
aEntities['&ecirc;'] = '&#234;';
aEntities['&euml;'] = '&#235;';
aEntities['&igrave;'] = '&#236;';
aEntities['&iacute;'] = '&#237;';
aEntities['&icirc;'] = '&#238;';
aEntities['&iuml;'] = '&#239;';
aEntities['&eth;'] = '&#240;';
aEntities['&ntilde;'] = '&#241;';
aEntities['&ograve;'] = '&#242;';
aEntities['&oacute;'] = '&#243;';
aEntities['&ocirc;'] = '&#244;';
aEntities['&otilde;'] = '&#245;';
aEntities['&ouml;'] = '&#246;';
aEntities['&divide;'] = '&#247;';
aEntities['&oslash;'] = '&#248;';
aEntities['&ugrave;'] = '&#249;';
aEntities['&uacute;'] = '&#250;';
aEntities['&ucric;'] = '&#251;';
aEntities['&uuml;'] = '&#252;';
aEntities['&yacute;'] = '&#253;';
aEntities['&thorn;'] = '&#254;';
aEntities['&yuml;'] = '&#255;';


/*

var aEmptyAttributes = new Array('compact', 'nowrap', 'ismap', 'declare', 'noshade', 'checked', 'disabled', 'readonly', 'multiple', 'selected', 'noresize', 'defer');

var aEmptyTags = new Array('br', 'img', 'input', 'hr', 'link', 'meta', 'area', 'param', 'base', 'basefont');

var aFlushAfter = new Array('td', 'table');

//p, span, a, div, b, u, i, td, tr, img, table, input, li, ol, script, textarea, strong

// This order results in 10% faster parsing!
var aTags = new Array();
aTags[0] = "p";             aTags[1] = "span";		    aTags[2] = "a";	          aTags[3] = "div";
aTags[4] = "b";		          aTags[5] = "u";	          aTags[6] = "i";	          aTags[7] = "td";
aTags[8] = "img";		        aTags[9] = "table";	      aTags[10] = "input";	    aTags[11] = "li";
aTags[12] = "ol";	          aTags[13] = "script";	    aTags[14] = "br";	        aTags[15] = "textarea";
aTags[16] = "strong";		    aTags[17] = "center";	    aTags[18] = "cite";	      aTags[19] = "code";
aTags[20] = "col";		      aTags[21] = "colgroup";	  aTags[22] = "dd";	        aTags[23] = "del";
aTags[24] = "dir";		      aTags[25] = "dfn";	      aTags[26] = "acronym";	  aTags[27] = "dl";
aTags[28] = "dt";		        aTags[29] = "em";	        aTags[30] = "fieldset";	  aTags[31] = "font";
aTags[32] = "form";		      aTags[33] = "frame";	    aTags[34] = "frameset";	  aTags[35] = "h1";
aTags[36] = "h2";		        aTags[37] = "h3";	        aTags[38] = "h4";	        aTags[39] = "h5";
aTags[40] = "h6";		        aTags[41] = "head";	      aTags[42] = "hr";	        aTags[43] = "html";
aTags[44] = "area";		      aTags[45] = "iframe";	    aTags[46] = "base";	      aTags[47] = "bdo";
aTags[48] = "ins";		      aTags[49] = "isindex";	  aTags[50] = "kbd";	      aTags[51] = "label";
aTags[52] = "legend";		    aTags[53] = "big";	      aTags[54] = "link";	      aTags[55] = "map";
aTags[56] = "menu";		      aTags[57] = "meta";	      aTags[58] = "noframes";	  aTags[59] = "noscript";
aTags[60] = "object";		    aTags[61] = "blockquote";	aTags[62] = "optgroup";	  aTags[63] = "option";
aTags[64] = "!doctype";		  aTags[65] = "param";	    aTags[66] = "pre";	      aTags[67] = "q";
aTags[68] = "s";		        aTags[69] = "samp";	      aTags[70] = "body";	      aTags[71] = "select";
aTags[72] = "small";		    aTags[73] = "abbr";	      aTags[74] = "strike";	    aTags[75] = "caption";
aTags[76] = "style";		    aTags[77] = "sub";	      aTags[78] = "sup";	      aTags[79] = "basefont";
aTags[80] = "tbody";		    aTags[81] = "address";	  aTags[82] = "button";	    aTags[83] = "tfoot";
aTags[84] = "th";		        aTags[85] = "thead";	    aTags[86] = "title";	    aTags[87] = "tr";
aTags[88] = "tt";		        aTags[89] = "applet";	    aTags[90] = "ul";	        aTags[91] = "v";

*/

/*var aTags = new Array();
aTags[0] = "!doctype";      aTags[1] = "a";		        aTags[2] = "abbr";	      aTags[3] = "acronym";
aTags[4] = "address";		    aTags[5] = "applet";	    aTags[6] = "area";	      aTags[7] = "b";
aTags[8] = "base";		      aTags[9] = "basefont";	  aTags[10] = "bdo";	      aTags[11] = "big";
aTags[12] = "blockquote";	  aTags[13] = "body";	      aTags[14] = "br";	        aTags[15] = "button";
aTags[16] = "caption";		  aTags[17] = "center";	    aTags[18] = "cite";	      aTags[19] = "code";
aTags[20] = "col";		      aTags[21] = "colgroup";	  aTags[22] = "dd";	        aTags[23] = "del";
aTags[24] = "dir";		      aTags[25] = "dfn";	      aTags[26] = "div";	      aTags[27] = "dl";
aTags[28] = "dt";		        aTags[29] = "em";	        aTags[30] = "fieldset";	  aTags[31] = "font";
aTags[32] = "form";		      aTags[33] = "frame";	    aTags[34] = "frameset";	  aTags[35] = "h1";
aTags[36] = "h2";		        aTags[37] = "h3";	        aTags[38] = "h4";	        aTags[39] = "h5";
aTags[40] = "h6";		        aTags[41] = "head";	      aTags[42] = "hr";	        aTags[43] = "html";
aTags[44] = "i";		        aTags[45] = "iframe";	    aTags[46] = "img";	      aTags[47] = "input";
aTags[48] = "ins";		      aTags[49] = "isindex";	  aTags[50] = "kbd";	      aTags[51] = "label";
aTags[52] = "legend";		    aTags[53] = "li";	        aTags[54] = "link";	      aTags[55] = "map";
aTags[56] = "menu";		      aTags[57] = "meta";	      aTags[58] = "noframes";	  aTags[59] = "noscript";
aTags[60] = "object";		    aTags[61] = "ol";	        aTags[62] = "optgroup";	  aTags[63] = "option";
aTags[64] = "p";		        aTags[65] = "param";	    aTags[66] = "pre";	      aTags[67] = "q";
aTags[68] = "s";		        aTags[69] = "samp";	      aTags[70] = "script";	    aTags[71] = "select";
aTags[72] = "small";		    aTags[73] = "span";	      aTags[74] = "strike";	    aTags[75] = "strong";
aTags[76] = "style";		    aTags[77] = "sub";	      aTags[78] = "sup";	      aTags[79] = "table";
aTags[80] = "tbody";		    aTags[81] = "td";	        aTags[82] = "textarea";	  aTags[83] = "tfoot";
aTags[84] = "th";		        aTags[85] = "thead";	    aTags[86] = "title";	    aTags[87] = "tr";
aTags[88] = "tt";		        aTags[89] = "u";	        aTags[90] = "ul";	        aTags[91] = "v";*/