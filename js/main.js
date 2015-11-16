String.prototype.turkishToUpper = function(){
    var string = this;
    var letters = { "i": "İ", "ş": "Ş", "ğ": "Ğ", "ü": "Ü", "ö": "Ö", "ç": "Ç", "ı": "I" };
    string = string.replace(/(([iışğüçö]))+/g, function(letter){ return letters[letter]; })
    return string.toUpperCase();
}

String.prototype.turkishToLower = function(){
    var string = this;
    var letters = { "İ": "i", "I": "ı", "Ş": "ş", "Ğ": "ğ", "Ü": "ü", "Ö": "ö", "Ç": "ç" };
    string = string.replace(/(([İIŞĞÜÇÖ]))+/g, function(letter){ return letters[letter]; })
    return string.toLowerCase();
}
String.prototype.trim = function() {
	return this.replace(/\s/g, ''); 
}
function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}
function isInt(n){
    return Number(n) === n && n % 1 === 0;
}

function isFloat(n){
    return n === Number(n) && n % 1 !== 0;
}
function isNumeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}
jQuery(function($){
	$.datepicker.regional['tr'] = {
	closeText: 'kapat',
	prevText: 'geri',
	nextText: 'ileri',
	currentText: 'bugün',
	monthNames: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],
	monthNamesShort: ['Oca','Şub','Mar','Nis','May','Haz','Tem','Ağu','Eyl','Eki','Kas','Ara'],
	dayNames: ['Pazar','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi'],
	dayNamesShort: ['Pz','Pt','Sa','Ça','Pe','Cu','Ct'],
	dayNamesMin: ['Pz','Pt','Sa','Ça','Pe','Cu','Ct'],
	weekHeader: 'Hf',
	dateFormat: 'dd/mm/yy',
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['tr']);
});
/**
 * Number.prototype.format(n, x, s, c)
 * @param integer n: length of decimal
 * @param integer x: length of whole part
 * @param mixed   s: sections delimiter
 * @param mixed   c: decimal delimiter
 */
Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};