import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

export const store = new Vuex.Store({
    plugins: [createPersistedState()],
    state: {
        categories: ['travel', 'fun', 'social', 'work', 'other'],
        invoices: [],
        bilties: [],
        challans: [],
        editBilty: {},
    },
    getters: {
        cat: state => {
            return state.categories;
        },
        getInvoiceById: state => invoice_no => {
            console.log('get called')
            return state.invoices.find(invoice => invoice.invoice_no == invoice_no);
        },
        getBiltyById: state => (bilty_no, manual) => {
            console.log(state.bilties)
            console.log('get called', bilty_no)
            return state.bilties.find(bilty => bilty.bilty_no == bilty_no && bilty.manual == manual);
        },
        getChallanById: state => (challan_no) => {
            console.log(state.challans)
            console.log('get called', challan_no)
            return state.challans.find(challan => challan.challan_no == challan_no);
        },
        getEditBiltyById: state => (id) => {
            console.log(state.editBilty)
            console.log('get called', id)
            if (state.editBilty.id == id) {
                return state.editBilty;
            }
        },
        invoices: state => {
            return state.invoices;
        },
    },
    mutations: {
        ADD_INVOICE(state, invoice) {
            state.invoices.push(invoice);
        },
        DESTROY_INVOICE(state) {
            state.invoices = [];
        },
        ADD_BILTY(state, bilty) {
            state.bilties.push(bilty);
        },
        DESTROY_BILTY(state) {
            state.bilties = [];
        },
        ADD_CHALLAN(state, challan) {
            state.challans.push(challan);
        },
        DESTROY_CHALLAN(state) {
            state.challans = [];
        },
        EDIT_BILTY(state, bilty) {
            state.editBilty = bilty;
        },
        DESTROY_EDIT_BILTY(state) {
            state.editBilty = {};
        },
    },
    actions: {
        createInvoice({ commit }, invoice) {
            commit('ADD_INVOICE', invoice);
        },
        destroyInvoice({ commit }) {
            commit('DESTROY_INVOICE');
        },
        createBilty({ commit }, bilty) {
            commit('ADD_BILTY', bilty);
        },
        destroyBilty({ commit }) {
            commit('DESTROY_BILTY');
        },
        createEditBilty({ commit }, bilty) {
            commit('EDIT_BILTY', bilty);
        },
        destroyEditBilty({ commit }) {
            commit('DESTROY_EDIT_BILTY');
        },
        createChallan({ commit }, challan) {
            commit('ADD_CHALLAN', challan);
        },
        destroyChallan({ commit }) {
            commit('DESTROY_CHALLAN');
        },
    },
});