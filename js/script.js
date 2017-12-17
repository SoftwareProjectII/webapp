$(document).ready(function()
    {
        $("#availableTrainingTable").tablesorter({

            widthFixed: true,

            widgets : ["filter"],

            widgetOptions : {
                filter_cssFilter: [
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control',
                ]
            },

            // sooner sessions first
            sortList: [[2,0]],
        });
        $("#registeredTrainingTable").tablesorter({

            widthFixed: true,

            widgets : ["filter"],

            widgetOptions : {
                filter_cssFilter: [
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control',
                ]
            },

            // sooner sessions first
            sortList: [[2,0]],
        });
        $("#myConfirmationsTrainingTable").tablesorter({

            widthFixed: true,

            widgets : ["filter"],

            widgetOptions : {
                filter_cssFilter: [
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control'
                ]
            },

            // sooner sessions first
            sortList: [[4,0]],
        });
    }
);