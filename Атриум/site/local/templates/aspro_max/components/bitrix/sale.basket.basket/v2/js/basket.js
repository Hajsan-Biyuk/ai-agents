function addShareBasket(parent) {
    if (arAsproOptions.PRICES.MIN_PRICE && 
        arAsproOptions.PRICES.MIN_PRICE > BX.Sale.BasketComponent.result.allSum) {
        BX.remove(document.querySelector(".basket-checkout-block-share"));
    } 
    else if (
        !document.querySelector(".basket-checkout-block-share") &&
        BX.Sale &&
        BX.Sale.BasketComponent &&
        "items" in BX.Sale.BasketComponent
    ) {
        if (!parent || !parent.parentNode) {
            console.warn("addShareBasket: parent отсутствует или не в DOM", parent);
            return;
        }

        const btnShareBasket = BX.create({
            tag: "div",
            attrs: {
                class: "btn_basket_heading btn_basket_heading--with_title basket-checkout-block basket-checkout-block-share colored_theme_hover_bg-block",
                title: arAsproOptions.THEME.EXPRESSION_FOR_SHARE_BASKET
            },
            html: '<span class="animate-load" data-event="jqm" data-param-form_id="share_basket" data-name="share_basket">' +
                  '<i class="svg colored_theme_hover_bg-el-svg">...</i>' +
                  '<span class="title">' + arAsproOptions.THEME.EXPRESSION_FOR_SHARE_BASKET + "</span></span>"
        });

        parent.parentNode.insertBefore(btnShareBasket, parent);
    }
}
