/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'Magento_Ui/js/grid/columns/expandable',
], function (_, Element) {
    'use strict';

    return Element.extend({
        defaults: {
            bodyTmpl: 'Unicorn_MagicUpdate/grid/cells/expandable',
            tooltipTmpl: 'Unicorn_MagicUpdate/grid/cells/tooltip',
        },

        /**
         *
         * @param record
         * @return {*}
         */
        getTooltip: function (record) {
            return record['description'];
        },
    });
});
