
var _app = {

    lang : {
    
        translations : {},
        
        l : function( text ){
            
            if( typeof( _app.lang.translations[text] ) !== 'undefined' ){
                
                return _app.lang.translations[text];
            }
            else{
                
                return text;
            }
        }
    },
    
    flashes: {

        el: '#flash-message',
        show_timeout: 2000,
        init: function () {

            if ($(_app.flashes.el).length) {

                setTimeout(function () {
                    _app.flashes.hide();
                }, _app.flashes.show_timeout);
            }
        },
        hide: function () {

            $(_app.flashes.el).fadeOut(1000);
        },
        show: function (text, type) {

            if ($(_app.flashes.el).length) {
                $(_app.flashes.el).remove();
            }

            if (typeof (type) === 'undefined') {
                type = 'success';
            }

            var flash = $('<div>')
                    .attr('id', _app.flashes.el.replace('#', ''))
                    .addClass('alert')
                    .addClass('alert-' + type)
                    .text(text);

            $('body').append(flash);

            setTimeout(function () {
                _app.flashes.hide();
            }, _app.flashes.show_timeout);
        }
    },
    
    captcha : {
        
        init : function() {
            
            $('form input[name^=__cc]').each( function() { 
                
                $(this).val( $(this).attr('name').substring(4).split('').sort().join('') );
            });
            
        }
    },
    
    form : {
        
        init: function() {
            
            _app.form.error.init();
            
            $(document).on('submit','form', function( e ) {
                
                var required_input_size = $('.required', $(this) ).length;
                var form_el = $(this);
                
                if( form_el.hasClass('verified') ){
                    
                    return true;
                }
                else{
                    
                    e.preventDefault();

                    if( required_input_size ){ 

                        var i = 0;
                        $('.required', $(this) ).each(function(){

                            if( !$(this).val().length ){

                                _app.form.error.show( $(this) );
                                _app.flashes.show( _app.lang.l( 'Please fill all required fields' ) , 'danger' );
                                return false;
                            }

                            if( ++i == required_input_size ){

                                form_el.addClass('verified').submit();
                                return true;
                            }
                        });
                    }
                    else{

                        form_el.addClass('verified').submit();
                        return true;
                    }
                }
                
            });
        },
        
        error : {
            
            init : function(){
                
                $(document).on('change focus','.error',function(){
                   
                    _app.form.error.hide( $(this) );
                });
            },
            show : function( el ){
                
                el.addClass('error');
            },
            hide : function ( el ){
                
                el.removeClass('error');
            }
        }
        
    },
    
    modals : {
        
        init: function(){
          
            // close modal after Esc key
            $(document).keyup(function(e) {
                
                if (e.key === "Escape") { 
                    
                    $('.modal').each(function(){
                        
                        if( $(this).css('display') == 'block' ){
                            $(this).hide();
                        }
                    });
                }
            });

        },
            
        removeItem : function( id , name ){

            var el = $('#removeItemModal');
            $('.btn-primary' , el).attr( 'href', el.attr('data-url') + id );
            $('.modal-header p' , el).text( '"' + name + '"' );
            el.show();
            
            return false;
        },
        
        configItemList : function( id , name ){
            
            var el = $('#configTenantModal');
            $('input[name=tenant_id]', el).val( id );
            $('input[name=tenant_name]' , el).val( name );
            el.show();
            
            return false;
        }

    },
        
    init: function () {

        _app.flashes.init();
        _app.captcha.init();
        _app.form.init();
        _app.modals.init();
    }

}
$(function () {

    _app.init();

})