document.addEventListener('DOMContentLoaded', async () => {
    if (typeof pkfavorites === 'undefined') {
        console.error('Promokit Favorites module is not installed properly. Try to reset it.');
        return;
    }

    let context = {};

    const favoritesController = 'module-pkfavorites-account';
    const animationTime = 700; // ms

    const actions = {
        add: 'add',
        remove: 'remove',
    };
    const selectors = {
        sidebar: '.js-tab-el-favorites',
        counter: '.js-pkfavorites-counter',
        dropdown: '.js-pkfavorites-container',
        iconActive: 'icon_checked',
        inProgress: 'in_progress',
        button: '.favorites-button',
        dataAttr: 'productsnum',
        mainParent: prestashop.themeSelectors.listing.product,
        hidden: prestashop.themeSelectors.alysum.classes.hidden,
    };

    const setContext = (target) => {
        const { action, pid } = target.dataset;
        const isAdd = action === actions.add;
        Object.assign(context, {
            button: target,
            url: isAdd ? pkfavorites.add : pkfavorites.remove,
            message: isAdd ? pkfavorites.phrases.added : pkfavorites.phrases.removed,
            buttonTitle: isAdd ? pkfavorites.phrases.remove : pkfavorites.phrases.add,
            state: isAdd ? 'success' : 'info',
            action,
            pid,
        });
    };

    const setData = (data) => {
        context = { ...context, data };
    };

    const addEventListeners = () => {
        const buttons = document.querySelectorAll(selectors.button);
        buttons?.forEach((button) => {
            if (!button.dataset.listenersAdded) {
                button.addEventListener('click', handleButtonClick);
                button.dataset.listenersAdded = true;
            }
        });
    };

    const handleButtonClick = async (event) => {
        event.preventDefault();

        if (!event.target.dataset.pid) {
            displayMessage('Invalid product ID', 'error');
            return;
        }

        setContext(event.target);

        loaderToggler(true);

        await makeRequest();
        await updateButton();

        renderProducts();
        updateOverallCounter();
        updateProductCounter(); // update button counter of clicked product if it's enabled

        loaderToggler(false);

        addEventListeners();

        displayMessage(context.message, context.state);
    };

    const loaderToggler = (action) => {
        const buttonSpan = context.button.querySelector('span');
        const loaderElement = buttonSpan || context.button;

        loaderElement.classList.toggle(selectors.inProgress, action);
    };

    const makeRequest = async () => {
        const url = `${context.url}&id_product=${context.pid}`;

        try {
            const response = await fetch(url, { method: 'POST' });

            if (!response.ok) throw new Error(`Error ${response.status}`);

            const data = await response.json();

            if (!data) throw new Error('Empty response');

            setData(data);
        } catch (e) {
            displayMessage(e, 'error');
        }
    };

    /**
     * Render a popup message
     * @param {string} message - a text message to show
     * @param {string} state - a state of error
     */
    const displayMessage = (message, state) => {
        $.jGrowl(message, {
            theme: `${$.jGrowl.defaults.theme} ${state}`,
            header: pkfavorites.phrases.title,
        });

        state === 'error' && console.error(message);
    };

    const updateButton = () => {
        context.button.dataset.action = {
            add: actions.remove,
            remove: actions.add,
        }[context.action];
        context.button.classList.toggle(selectors.iconActive);
        context.button.setAttribute('title', context.buttonTitle);

        const buttonTitle = context.button.querySelector('span');
        buttonTitle && (buttonTitle.textContent = context.buttonTitle);

        const isSidebar = context.button.closest(selectors.sidebar);

        if (
            context.action === actions.remove &&
            (isSidebar || prestashop.page.page_name === favoritesController)
        ) {
            hideProduct();
        }

        return wait();
    };

    const hideProduct = () => {
        const rootElement = context.button.closest(selectors.mainParent);
        rootElement.style.transition = `all ${animationTime}ms ease-in-out`;
        rootElement.style.opacity = 0;
    };

    const renderProducts = () => {
        const dropdown = document.querySelector(selectors.dropdown);
        const sidebar = document.querySelector(selectors.sidebar);

        if (sidebar) {
            sidebar.innerHTML = context.data.products;
        }

        if (dropdown) {
            dropdown.innerHTML = context.data.miniproducts;
            dropdown.parentElement.classList.toggle(selectors.hidden, context.data.products_number <= 0);
            dropdown.parentElement.style.height = 'auto';
        }
    };

    const updateProductCounter = () => {
        const counter = context.button.querySelector('i');

        if (!counter || !context.data.products_number) return;

        counter.dataset[selectors.dataAttr] = context.data.products_number;
        counter.textContent = context.data.context.data.products_number;
    };

    const updateOverallCounter = () => {
        const counter = document.querySelector(selectors.counter);

        if (!counter) return;

        const qty = context.data.products_number || 0;

        counter.dataset[selectors.dataAttr] = qty;
        counter.textContent = qty;
    };

    const wait = () => {
        return new Promise((resolve) => setTimeout(resolve, this.animationTime));
    };

    addEventListeners();

    prestashop.on('pkelementsTabsReady', () => addEventListeners());
});
