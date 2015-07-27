var options = [];
//set cookie
var cookieCompanies = getCookie('companies');
if(cookieCompanies){
	options = JSON.parse(cookieCompanies);
	for(var i=0; i<options.length; i++){
		$('#comp_'+options[i]).prop('checked', true);
	}
}

$( '.dropdown-menu a' ).on( 'click', function( event ) {
   var $target = $( event.currentTarget ),
       val = $target.attr( 'data-value' ),
       $inp = $target.find( 'input' ),
       idx;

   if ( ( idx = options.indexOf( val ) ) > -1 ) {
      options.splice( idx, 1 );
      setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
   } else {
      options.push( val );
      setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
   }

   $( event.target ).blur();
   $('#num_of_selected').html(options.length);
   options = options.filter(function(n){ return n != undefined });//check for null before set again
   setCookie('companies', JSON.stringify(options), 1);
   
   return false;
});