import Vue from 'Vue';
import VueRouter from 'vue-router';
import Login from './components/Authentication/Login';
import Register from './components/Authentication/Register';
import AdminDashboard from './components/AdminDashboard';
import AdminDashboardStats from './components/Admin/AdminDashboardStats';
import OperatorDashboardStats from './components/Operator/OperatorDashboardStats';
import OperatorDashboard from './components/OperatorDashboard';
import Home from './components/Home';
import AddBilty from './components/Bilty/Add';
import ManageBilty from './components/Bilty/Manage';
import EditBilty from './components/Bilty/Edit';
import AddChallan from './components/Challan/Add';
import ManageChallan from './components/Challan/Manage';
import EditChallan from './components/Challan/Edit';
import AddCustomer from './components/Customer/Add';
import ManageCustomer from './components/Customer/Manage';
import CustomerAccount from './components/Accounts/Customer';
import Package from './components/Packages/Package';
import BiltyReport from './components/Reports/Bilty';
import ChallanReport from './components/Reports/Challan';
import PageNotFound from './components/Errors/PageNotFound';
import ForbiddenPage from './components/Errors/ForbiddenPage';


const routes = [
    { path: '*', component: PageNotFound },
    { path: '/404', component: PageNotFound },
    { path: '/403', component: ForbiddenPage },
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            auth: undefined
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }
    },
    //OPERATOR
    {
        path: '/dashboard',
        component: OperatorDashboard,
        meta: {
            auth: { roles: [1, 3, 4], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
        },
        children: [
            {
                path: '',
                name: 'dashboard',
                component: OperatorDashboardStats,
            },
            {
                path: 'bilty/manage',
                name: 'dashboard.bilty.manage',
                component: ManageBilty,
            },
            {
                path: 'bilty/create',
                name: 'dashboard.bilty.create',
                component: AddBilty,
                meta: {
                    auth: { roles: [1, 3], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
                },
            },
            //FURTHUR WORK ON AUTHENTICATION REQUIRED
            {
                path: 'account/customer',
                name: 'dashboard.account.customer',
                component: CustomerAccount,
            },
            {
                path: 'challan/create',
                name: 'dashboard.challan.create',
                component: AddChallan,
                meta: {
                    auth: { roles: [1, 3], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
                },
            },
            {
                path: 'challan/manage',
                name: 'dashboard.challan.manage',
                component: ManageChallan
            },
            {
                path: 'customer/create',
                name: 'dashboard.customer.create',
                component: AddCustomer,
                meta: {
                    auth: { roles: [1, 3], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
                },
            },
            {
                path: 'customer/manage',
                name: 'dashboard.customer.manage',
                component: ManageCustomer
            },
            {
                path: 'package',
                name: 'dashboard.package',
                component: Package
            },
            {
                path: 'report/bilty',
                name: 'dashboard.report.bilty',
                component: BiltyReport
            },
            {
                path: 'report/challan',
                name: 'dashboard.report.challan',
                component: ChallanReport
            },
        ]
    },
    //ADMIN
    {
        path: '/admin',
        component: AdminDashboard,
        meta: {
            auth: { roles: 2, redirect: { name: 'login' }, forbiddenRedirect: '/403' }
        },
        children: [
            {
                path: '',
                name: 'admin.dashboard.stats',
                component: AdminDashboardStats,
            },
            {
                path: 'bilty/manage',
                name: 'admin.bilty.manage',
                component: ManageBilty,
            },
            {
                path: 'bilty/create',
                name: 'admin.bilty.create',
                component: AddBilty,
            },
            {
                path: 'bilty/edit/:id',
                name: 'admin.bilty.edit',
                component: EditBilty,
            },
            //FURTHUR WORK ON AUTHENTICATION REQUIRED
            {
                path: 'account/customer',
                name: 'admin.account.customer',
                component: CustomerAccount,
            },
            {
                path: 'challan/create',
                name: 'admin.challan.create',
                component: AddChallan
            },
            {
                path: 'challan/manage',
                name: 'admin.challan.manage',
                component: ManageChallan
            },
            {
                path: 'challan/edit/:id',
                name: 'admin.challan.edit',
                component: EditChallan
            },
            {
                path: 'customer/create',
                name: 'admin.customer.create',
                component: AddCustomer
            },
            {
                path: 'customer/manage',
                name: 'admin.customer.manage',
                component: ManageCustomer
            },
            {
                path: 'package',
                name: 'admin.package',
                component: Package
            },
            {
                path: 'report/bilty',
                name: 'admin.report.bilty',
                component: BiltyReport
            },
            {
                path: 'report/challan',
                name: 'admin.report.challan',
                component: ChallanReport
            },
        ]
    },

]

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
})

export default router