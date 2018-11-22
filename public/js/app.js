/**
 *
 */
$.validator.addMethod('validPassword',
    function (value, element, param){
        if(value != ''){
            return value.match(/.*\d+.*/) != null && value.match(/.*[a-z]+.*/) != null;
        }
    },
    'Must contain at least one letter and one number'
);