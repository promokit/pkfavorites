/** Class representing Favorite Products feature */
class Favorites {

    animationTime = 700; // ms

    /**
     * Crates an event listeners for Favorite buttons
     */
    constructor() {
        if (typeof pkfavorites === 'undefined') {
            return console.error('Promokit Favorites module is not installed properly. Try to reset it.');
        }
        document.body.addEventListener('click', this.clickHandler.bind(this));
    }

    /**
     * Get the list of classes
     * @return {object} 
     */
    get classes() {
        return {
            sidebar: 'js-tab-favorites',
            counter: 'js-pkfavorites-counter',
            dropdown: 'js-pkfavorites-container',
            iconActive: 'icon_checked',
            inProgress: 'in_progress',
            buttonType: 'icon-button',
            mainParent: 'product-miniature',
            button: 'favoritesButton',
            hidden: 'hidden'
        }
    }

    clickHandler(event) {
        const btn = event.target;

        // check if "favorite" button clicked
        if (!btn.classList.contains(this.classes.button)) return;

        event.preventDefault();

        // define the context depends on action
        const context = this.setContext(btn.dataset.action);

        this.doAction(btn, context);
    }

    /**
     * Set the context of current event
     * @param {string} action - what action to do
     * @returns {object} Event context
     */
    setContext(action) {
        const context = {
            url: pkfavorites.add,
            message: pkfavorites.phrases.added,
            button: pkfavorites.phrases.remove,
            state: 'success',
            action
        };

        if (action === 'remove') {
            context.url = pkfavorites.remove,
            context.message = pkfavorites.phrases.removed,
            context.button = pkfavorites.phrases.add,
            context.state = 'info'
        }

        return context;
    }

    async doAction(btn, context) {
        // get product ID
        const pid = Number(btn.dataset.pid);

        if (!pid) throw new Error('Wrong Product ID');

        // add "in progress" loader
        this.loaderToggler(btn, 'on');

        // make a request
        const data = await this.makeRequest(context, pid);

        // update button view and state
        await this.updateButton(btn, context);

        // update favorite products list
        this.renderProducts(data);

        // update product counter
        this.updateCounter(data.products_number);

        // display success message
        this.displayMessage(context.message, context.state);
    }

    /**
     * Make a reqest to module controller
     * @param {object} Context of current event
     * @param {string} pid - the ID of a product
     * @returns {object} - ready to render data§
     */
    async makeRequest(context, pid) {
        try {
            const url = `${context.url}&id_product=${pid}`;
            const response = await fetch(url, { method: 'POST' });

            if (!response.ok) throw new Error(`Error ${response.status}`);

            const data = await response.json();

            if (!data) throw new Error('Empty response');

            return data;

        } catch(e) {
            this.displayMessage(e, 'error')
        }
    }

    /**
     * Toggle button loader
     * @param {object} btn - current button element
     * @param {*} action - what action to do
     */
    loaderToggler(btn, action) {
        action === 'on' && btn.classList.add(this.classes.inProgress);
        action === 'off' && btn.classList.remove(this.classes.inProgress);
    }

    /**
     * Update button view and state
     * @param {object} btn - current button element
     * @param {object} context - current button context
     * @returns {Promise} Promise object that just make a delay
     */
    updateButton(btn, context) {
        // toggle button "action" attribute and "active" class
        btn.dataset.action = context.action === 'add' ? 'remove' : 'add';
        btn.classList.toggle(this.classes.iconActive);

        // update button title
        btn.setAttribute('title', context.button);

        // if button has text element
        const btnTitle = btn.querySelector('span');
        btnTitle && (btnTitle.textContent = context.button);

        // remove "in progress" loader
        this.loaderToggler(btn, 'off');

        // animate product removing from sidebar
        const isSidebar = btn.closest(`.${this.classes.sidebar}`);

        if (context.action === 'remove' && isSidebar) {
            this.hideProduct(btn);
        }

        // return delay (.7s) promise to show animation
        return this.wait();
    }

    /**
     * Hide product instance in a sidebar or dropdown sections
     * @param {object} btn - current button element
     */
    hideProduct(btn) {
        btn.closest(`.${this.classes.mainParent}`).style.transition = `all ${this.animationTime}ms ease-in-out`;
        btn.closest(`.${this.classes.mainParent}`).style.opacity = 0;   
    }

    /**
     * Render a list of favorite products in a sidebar or dropdown sections
     * @param {object} data 
     * @returns nothing
     */
    renderProducts(data) {
        const dropdown = document.querySelector(`.${this.classes.dropdown}`);
        const sidebar = document.querySelector(`.${this.classes.sidebar}`);

        // if sidebar exist, render product list there
        sidebar && (sidebar.innerHTML = data.products);

        if (!dropdown) return;

        // fix dropdown wrong height
        dropdown.parent.style.height = 'auto';

        // update product list
        dropdown.innerHTML = data.miniproducts;

        // update visual state classes
        data.products_number > 0 && dropdown.parent.classList.remove(this.classes.hidden);
        data.products_number <= 0 && dropdown.parent.classList.add(this.classes.hidden);
    }

    /**
     * Update a counter of favorite products
     * @param {number} quantity 
     * @returns nothing
     */
    updateCounter(quantity) {
        const counter = document.querySelector(`.${this.classes.counter}`);

        if (!counter) return;

        counter.textContent = quantity;

        if (quantity > 0) counter.classList.remove(this.classes.hidden);
    }

    /**
     * Render a popup message 
     * @param {string} message - a text message to show
     * @param {string} state - a state of error
     */
    displayMessage(message, state) {
        $.jGrowl(message, {
            theme: `${$.jGrowl.defaults.theme} ${state}`,
            header: pkfavorites.phrases.title
        });

        state === 'error' && console.error(message);
    }

    /**
     * Set a timeout 
     * @returns {Promise} Promise object that just make a delay
     */
    wait() {
        return new Promise(resolve => setTimeout(resolve, this.animationTime));
    }
}
new Favorites();