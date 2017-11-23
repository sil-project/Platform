$(document).ready(function() {

    var BulkSelector = function() {

        /**
         * The calculator precision length
         *
         * @type {Number}
         */
        this.decimals = 3;

        /**
         * Boolean to enable or disable bulk form
         *
         * @type {Boolean}
         */
        this.enabled = true;

        /**
         * Packaging select input
         *
         * @type {Object}
         */
        this.packagingSelect = null;

        /**
         * The custom form wrapper
         *
         * @type {Object}
         */
        this.bulkForm = null;

        /**
         * The default quantity input
         *
         * @type {Object}
         */
        this.quantityChooser = null;

        /**
         * The variety seed density for calculation
         *
         * @type {Float}
         */
        this.varietyDensity = '';

        /**
         * The variety thousand kernel weight
         *
         * @type {Float}
         */
        this.varietyTkw = '';

        /**
         * The bulk variant unit price
         *
         * @type {String}
         */
        this.bulkUnitPrice = null;

        this.__construct = function() {
            // init properties
            this.packagingSelect = $('[name*="sylius_add_to_cart[cartItem][variant][_lisem_packaging]"]');
            this.bulkForm = $('#sil-product-bulk-chooser');
            this.quantityChooser = $('#sylius_add_to_cart_cartItem_quantity').parent();
            this.varietyDensity = this.bulkForm.find('input[name="product-variety-density"]').val();
            this.varietyTkw = this.bulkForm.find('input[name="product-variety-tkw"]').val();
            this.bulkUnitPrice = $('#sylius-variants-pricing').find('div[data-_lisem_packaging="BULK"]').data('value');

            // init form events
            this.initEvents();

            // Check if bulk form feature can be enabled
            this.checkEnabled();
        };

        this.checkEnabled = function() {
            // Disable bulk form if Density or Tkw is missing for current product variety
            if (this.varietyDensity === '' || this.varietyTkw === '') {
                this.enabled = false;
            }
            this.enableOrDisableBulkProductOption(this.enabled);
        };

        this.enableOrDisableBulkProductOption = function(enable) {
            let bulkFakeSelectItem = this.packagingSelect.parent().find('div.item[data-value="BULK"]');

            enable ? bulkFakeSelectItem.removeClass('disabled') : bulkFakeSelectItem.addClass('disabled');
        };

        this.initEvents = function() {
            var _self = this;
            var lastSourceInput = null;
            $(document)
                .on('keyup focusout', this.bulkForm.selector + ' input[name="bulk-weight"],' + this.bulkForm.selector + ' input[name="bulk-surface"]', function() {
                    _self.calculate($(this));
                    lastSourceInput = $(this);
                })
                .on('change', this.bulkForm.selector + ' select[name="bulk-weight-unit"],' + this.bulkForm.selector + ' select[name="bulk-surface-unit"]', function() {
                    let linkedFieldName = $(this).data('linked-field');
                    if (lastSourceInput === null) {
                        lastSourceInput = $('input[name="' + linkedFieldName + '"]');
                    }
                    _self.calculate(lastSourceInput);
                });
        };

        this.calculate = function(sourceInput) {
            let calculationType,
                destInput;

            this.computePrice(0);

            if (sourceInput.val() === '') {
                this.resetBulkForm();
                return;
            }

            calculationType = sourceInput.attr('name') === 'bulk-weight' ? 'weightToSurface' : 'surfaceToWeight';

            destInput = this.bulkForm.find('input[name="bulk-' + (calculationType === 'weightToSurface' ? 'surface' : 'weight') + '"]');

            var Density = this.varietyDensity;
            var Tkw = this.varietyTkw;

            var Weight;
            var WeightUnitRatio;
            var Surface;
            var SurfaceUnitRatio;

            var result = 0;
            var source = 0;

            WeightUnitRatio = this.numberToFixed(this.bulkForm.find('select[name="bulk-weight-unit"] option:selected').data('ratio'));
            SurfaceUnitRatio = this.numberToFixed(this.bulkForm.find('select[name="bulk-surface-unit"] option:selected').data('ratio'));

            if (calculationType === 'surfaceToWeight') {
                source = Surface = this.numberToFixed(sourceInput.val() * SurfaceUnitRatio);

                SeedNumber = Surface * Density;

                Weight = SeedNumber * Tkw / 1000;

                result = this.numberToFixed(Weight / WeightUnitRatio);
            } else {
                source = Weight = this.numberToFixed(sourceInput.val() * WeightUnitRatio);

                SeedNumber = Weight * 1000 / Tkw;

                Surface = SeedNumber / Density;

                result = this.numberToFixed(Surface / SurfaceUnitRatio);
            }
            this.computePrice(calculationType === 'surfaceToWeight' ? result : source);

            destInput.val(result);
        };

        this.computePrice = function(weight) {
            if (weight === 0) {
                $('#product-price').html(this.bulkUnitPrice);
                return;
            }

            const regex = /(\d+)[\,\.]?(\d*)\W?([\€\$])/g;
            const subst = '$1.$2';
            const substMoney = '$3';

            let bulkPrice = this.bulkUnitPrice.replace(regex, subst);
            let bulkMoney = this.bulkUnitPrice.replace(regex, substMoney);

            $('#product-price').html(this.numberToFixed(weight * bulkPrice, 2) + " " + bulkMoney);
        };

        this.numberToFixed = function(value, decimals) {
            if (typeof decimals === 'undefined' || decimals === null) {
                decimals = this.decimals;
            }
            return parseFloat(value.toString().replace(',', '.')).toFixed(decimals);
        };

        this.handleChange = function(select, value) {
            if (select.attr('id') === this.packagingSelect.attr('id')) {
                value === 'BULK' ? this.showBulkForm() : this.hideBulkForm();
            }
        };

        this.showBulkForm = function() {
            if (this.enabled) {
                this.bulkForm.show();
                this.bulkForm.find('input[name="product-is-bulk"]').val(1);
                this.quantityChooser.hide();
            }
        };

        this.hideBulkForm = function() {
            if (this.enabled) {
                this.quantityChooser.show();
                this.bulkForm.hide();
                this.bulkForm.find('input[name="product-is-bulk"]').val(0);
            }
        };

        this.resetBulkForm = function() {
            this.bulkForm.find('input[name="bulk-surface"]').val('');
            this.bulkForm.find('input[name="bulk-weight"]').val('');
        };

        this.__construct(); // Hack to simulate class constructor call when instanciating
    }

    // Init bulk selector
    window.selector = new BulkSelector();

    // Init if packaging
    window.selector.handleChange($(window.selector.packagingSelect.selector), $(window.selector.packagingSelect.selector).val());

    // Init packaging change event
    $(document).on('change', window.selector.packagingSelect.selector, function() {
        window.selector.handleChange($(this), $(this).val());
    });


});
