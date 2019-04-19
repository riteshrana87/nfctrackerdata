jQuery(function ($) {
    var fields = [
        {
            type: 'text',
            label: 'Custom Autocomplete',
            required: true,
            values: [
                {label: 'SQL'},
                {label: 'C#'},
                {label: 'JavaScript'},
                {label: 'Java'},
                {label: 'Python'},
                {label: 'C++'},
                {label: 'PHP'},
                {label: 'Swift'},
                {label: 'Ruby'}
            ]
        },
        {
            label: 'Star Rating',
            attrs: {
                type: 'starRating'
            },
            icon: '🌟'
        }
    ];
    var formAction = $('#editformjson').val();

    var actionButtons = [{
            id: 'smile',
            className: 'btn btn-success',
            label: '😁',
            type: 'button',
            events: {
                click: function () {
                    alert('😁😁😁 !SMILE! 😁😁😁');
                }
            }
        }];

    var templates = {
        starRating: function (fieldData) {
            return {
                field: '<span id="' + fieldData.name + '">',
                onRender: function () {
                    $(document.getElementById(fieldData.name)).rateYo({rating: 3.6});
                }
            };
        }
    };

    var inputSets = [{
            label: 'User Details',
            name: 'user-details', // optional
            showHeader: true, // optional
            fields: [{
                    type: 'text',
                    label: 'First Name',
                    className: 'form-control'
                }, {
                    type: 'select',
                    label: 'Profession',
                    className: 'form-control',
                    values: [{
                            label: 'Street Sweeper',
                            value: 'option-2',
                            selected: false
                        }, {
                            label: 'Brain Surgeon',
                            value: 'option-3',
                            selected: false
                        }]
                }, {
                    type: 'textarea',
                    label: 'Short Bio:',
                    className: 'form-control'
                }]
        }, {
            label: 'User Agreement',
            fields: [{
                    type: 'header',
                    subtype: 'h3',
                    label: 'Terms & Conditions',
                    className: 'header'
                }, {
                    type: 'paragraph',
                    label: 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.',
                }, {
                    type: 'paragraph',
                    label: 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.',
                }, {
                    type: 'checkbox',
                    label: 'Do you agree to the terms and conditions?',
                }]
        }];

    var typeUserDisabledAttrs = {
        autocomplete: ['access'],
        'checkbox-group': [
          'description',
          'toggle',
          'access',
          'other'
        ],
        'radio-group': [
          'description',
          'access',
          'other'
        ],
        'date': [
          'access'
        ],
        'number': [
          'access'
        ],
        'file': [
          'access',
          'subtype'
        ],
        'header': [
          'access'
        ],
        'hidden': [
          'access'
        ],
        'text': [
          'access'
        ],
        'textarea': [
          'access'
        ],
        'select': [
          'access',
          'multiple'
        ],
    };

    var typeUserAttrs = {
        textarea: {
          subtype: {
            options: {
                    'textarea': 'Text Area',
                    'tinymce': 'tinymce',
                }
            },
            displayonlist: {
                label: 'Display On List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonadminlist: {
                label: 'Display On Admin List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonalign: {
                label: 'Display On Left Or Right',
                options: {
                    '2': 'center',
                    '1': 'left',
                    '0': 'right',
                }
            },
        },
        select: {
            displayonlist: {
                label: 'Display On List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonadminlist: {
                label: 'Display On Admin List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonalign: {
                label: 'Display On Left Or Right',
                options: {
                    '2': 'center',
                    '1': 'left',
                    '0': 'right',
                }
            },
        },
        hidden: {
            displayonlist: {
                label: 'Display On List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonadminlist: {
                label: 'Display On Admin List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonalign: {
                label: 'Display On Left Or Right',
                options: {
                    '2': 'center',
                    '1': 'left',
                    '0': 'right',
                }
            },
        },
        header: {
            displayonlist: {
                label: 'Display On List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonadminlist: {
                label: 'Display On Admin List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonalign: {
                label: 'Display On Left Or Right',
                options: {
                    '2': 'center',
                    '1': 'left',
                    '0': 'right',
                }
            },
        },
        file: {
            displayonlist: {
                label: 'Display On List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonadminlist: {
                label: 'Display On Admin List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonalign: {
                label: 'Display On Left Or Right',
                options: {
                    '2': 'center',
                    '1': 'left',
                    '0': 'right',
                }
            },
        },
        number: {
            displayonlist: {
                label: 'Display On List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonadminlist: {
                label: 'Display On Admin List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonalign: {
                label: 'Display On Left Or Right',
                options: {
                    '2': 'center',
                    '1': 'left',
                    '0': 'right',
                }
            },
        },
        date: {
            displayonlist: {
                label: 'Display On List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonadminlist: {
                label: 'Display On Admin List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonalign: {
                label: 'Display On Left Or Right',
                options: {
                    '2': 'center',
                    '1': 'left',
                    '0': 'right',
                }
            },
        },
        text: {
            className: {
                label: 'Class',
                options: {
                    'red form-control': 'Red',
                    'green form-control': 'Green',
                    'blue form-control': 'Blue'
                },
                style: 'border: 1px solid red'
            },
            displayonlist: {
                label: 'Display On List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonadminlist: {
                label: 'Display On Admin List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonalign: {
                label: 'Display On Left Or Right',
                options: {
                    '2': 'center',
                    '1': 'left',
                    '0': 'right',
                }
            },
        },
        'radio-group': {
                displayonlist: {
                label: 'Display On List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonadminlist: {
                label: 'Display On Admin List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonalign: {
                label: 'Display On Left Or Right',
                options: {
                    '2': 'center',
                    '1': 'left',
                    '0': 'right',
                }
            },
        },
        'checkbox-group': {
                displayonlist: {
                label: 'Display On List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonadminlist: {
                label: 'Display On Admin List',
                options: {
                    '1': 'show',
                    '0': 'hide',
                }
            },
            displayonalign: {
                label: 'Display On Left Or Right',
                options: {
                    '2': 'center',
                    '1': 'left',
                    '0': 'right',
                }
            },
        },
        
    };

    // test disabledAttrs
    var disabledAttrs = ['placeholder'];

    var fbOptions = {
        subtypes: {
            text: ['time']
        },
        onSave: function (e, formData) {
            toggleEdit();
            $('.render-wrap').formRender({
                formData: formData,
                templates: templates
            });
            window.sessionStorage.setItem('formData', JSON.stringify(formData));
            $("#testSubmit").show();

        },
         
        stickyControls: {
            enable: true
        },
        sortableControls: true,
        //fields: fields,
        templates: templates,
        //inputSets: inputSets,
        typeUserDisabledAttrs: typeUserDisabledAttrs,
        typeUserAttrs: typeUserAttrs,
        disableInjectedStyle: false,
        //actionButtons: actionButtons,
        disabledActionButtons: ['data','clear'],
        disableFields: ['autocomplete','paragraph','button'],
        
                // controlPosition: 'left'
                // disabledAttrs
    };
     sessionStorage.removeItem('formData');
    var formData = window.sessionStorage.getItem('formData');
    var editing = true;

    if (formData) {
        fbOptions.formData = JSON.parse(formData);
    }

    /**
     * Toggles the edit mode for the demo
     * @return {Boolean} editMode
     */
    function toggleEdit() {
        document.body.classList.toggle('form-rendered', editing);
        return editing = !editing;
    }

    var setFormData = '[{"type":"text","label":"Full Name","subtype":"text","className":"form-control","name":"text-1476748004559"},{"type":"select","label":"Occupation","className":"form-control","name":"select-1476748006618","values":[{"label":"Street Sweeper","value":"option-1","selected":true},{"label":"Moth Man","value":"option-2"},{"label":"Chemist","value":"option-3"}]},{"type":"textarea","label":"Short Bio","rows":"5","className":"form-control","name":"textarea-1476748007461"}]';

    var fbTemplate = document.getElementById('build-wrap');
    
    if (formAction) {
        fbOptions.formData = JSON.parse(formAction);
    }
    var formBuilder = $('.build-wrap').formBuilder(fbOptions);


    //         var formBuilder = $('.build-wrap').formBuilder(fbOptions);
    // var formBuilder = $('.build-wrap').formBuilder(fbOptions);

//      if(formAction != ''){
//         var formBuilder = $('.build-wrap').formBuilder(options);
//     }else{
//        var formBuilder = $('.build-wrap').formBuilder(fbOptions);
//     }       
//    
//  var formBuilder = $('.build-wrap').formBuilder(fbOptions);
//  console.log(fbOptions);

    var fbPromise = formBuilder.promise;

    fbPromise.then(function (fb) {
        var apiBtns = {
            showData: fb.actions.showData,
            clearFields: fb.actions.clearFields,
            getData: function () {
                console.log(fb.actions.getData());
            },
            setData: function () {
                fb.actions.setData(setFormData);
            },
            addField: function () {
                var field = {
                    type: 'text',
                    class: 'form-control',
                    label: 'Text Field added at: ' + new Date().getTime()
                };
                fb.actions.addField(field);
            },
            removeField: function () {
                fb.actions.removeField();
            },
            testSubmit: function () {
                $('#formbuild_data_val').val($('.render-wrap').html());
                $('#form_json_data').val(formBuilder.actions.getData('json', true));

                $("#formbuild_data").submit();
                var formData = new FormData(document.forms[0]);
            },
            resetDemo: function () {
                window.sessionStorage.removeItem('formData');
                location.reload();
            }
        };

        Object.keys(apiBtns).forEach(function (action) {
            document.getElementById(action)
                    .addEventListener('click', function (e) {
                        apiBtns[action]();
                    });
        });

//        document.getElementById('setLanguage')
//                .addEventListener('change', function (e) {
//                    fb.actions.setLang(e.target.value);
//                });

        document.getElementById('getXML').addEventListener('click', function () {
            alert(formBuilder.actions.getData('xml'));
        });
        document.getElementById('getJSON').addEventListener('click', function () {
            alert(formBuilder.actions.getData('json', true));
        });
        document.getElementById('getJS').addEventListener('click', function () {
            alert('check console');
            console.log(formBuilder.actions.getData());
        });
    });

    document.getElementById('edit-form').onclick = function () {
        $("#testSubmit").hide();
        toggleEdit();
    };

    $('body').on('click', '.delete-confirm', function () {
        var mainli = $(this).closest('li').attr('id');
        var inparr = [];
        
        $('#' + mainli + ' .prev-holder input[type="checkbox"]').each(function () {
            if ($(this).attr('name') != '') {
                inparr.push($(this).attr('name'));
                var template = '<input type="hidden" name="field_data[]" value="'+$(this).attr('name')+'">';
                $('#deletefields').append(template);
            }
        });
        
        $('#' + mainli + ' .prev-holder input[type="date"]').each(function () {
            if ($(this).attr('name') != '') {
                inparr.push($(this).attr('name'));
                var template = '<input type="hidden" name="field_data[]" value="'+$(this).attr('name')+'">';
                $('#deletefields').append(template);
            }
        });
        
        $('#' + mainli + ' .prev-holder input[type="file"]').each(function () {
            if ($(this).attr('name') != '') {
                inparr.push($(this).attr('name'));
                var template = '<input type="hidden" name="field_data[]" value="'+$(this).attr('name')+'">';
                $('#deletefields').append(template);
            }
        });
        
        $('#' + mainli + ' .prev-holder input[type="hidden"]').each(function () {
            if ($(this).attr('name') != '') {
                inparr.push($(this).attr('name'));
                var template = '<input type="hidden" name="field_data[]" value="'+$(this).attr('name')+'">';
                $('#deletefields').append(template);
            }
        });
        
        $('#' + mainli + ' .prev-holder input[type="paragraph"]').each(function () {
            if ($(this).attr('name') != '') {
                inparr.push($(this).attr('name'));
                var template = '<input type="hidden" name="field_data[]" value="'+$(this).attr('name')+'">';
                $('#deletefields').append(template);
            }
        });
         $('#' + mainli + ' .prev-holder input[type="number"]').each(function () {
            if ($(this).attr('name') != '') {
                inparr.push($(this).attr('name'));
                var template = '<input type="hidden" name="field_data[]" value="'+$(this).attr('name')+'">';
                $('#deletefields').append(template);
            }
        });
        
        $('#' + mainli + ' .prev-holder input[type="radio"]').each(function () {
            if ($(this).attr('name') != '') {
                inparr.push($(this).attr('name'));
                var template = '<input type="hidden" name="field_data[]" value="'+$(this).attr('name')+'">';
                $('#deletefields').append(template);
            }
        });
        
        $('#' + mainli + ' .prev-holder select').each(function () {
            if ($(this).attr('name') != '') {
                inparr.push($(this).attr('name'));
                var template = '<input type="hidden" name="field_data[]" value="'+$(this).attr('name')+'">';
                $('#deletefields').append(template);
            }
        });
        
        $('#' + mainli + ' .prev-holder input[type="text"]').each(function () {
            if ($(this).attr('name') != '') {
                inparr.push($(this).attr('name'));
                var template = '<input type="hidden" name="field_data[]" value="'+$(this).attr('name')+'">';
                $('#deletefields').append(template);
            }
        });
       
         $('#' + mainli + ' .prev-holder textarea').each(function () {
            if ($(this).attr('name') != '') {
                inparr.push($(this).attr('name'));
                var template = '<input type="hidden" name="field_data[]" value="'+$(this).attr('name')+'">';
                $('#deletefields').append(template);
            }
        });
    });


});
