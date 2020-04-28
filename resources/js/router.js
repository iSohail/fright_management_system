
import VueRouter from 'vue-router';
import Login from './components/Authentication/Login';
import AdminDashboard from './components/AdminDashboard';
import AdminDashboardStats from './components/Admin/AdminDashboardStats';
import OperatorDashboardStats from './components/Operator/OperatorDashboardStats';
import OperatorDashboard from './components/OperatorDashboard';
import Home from './components/Home';
import AddBilty from './components/Bilty/Add';
import ManageBilty from './components/Bilty/Manage';
import MonthlyBilty from './components/Bilty/Monthly';
import ReceiveBilty from './components/Bilty/Receive';
import EditBilty from './components/Bilty/Edit';
import AddChallan from './components/Challan/Add';
import ManageChallan from './components/Challan/Manage';
import EditChallan from './components/Challan/Edit';
import AddCustomer from './components/Customer/Add';
import ManageCustomer from './components/Customer/Manage';
import Sender from './components/Customer/Sender';
import Receiver from './components/Customer/Receiver';
import CustomerAccount from './components/Accounts/Customer';
import ManageLedger from './components/Ledgers/Manage';
import EditLedger from './components/Ledgers/Edit';
import Package from './components/Packages/Package';
import BiltyReport from './components/Reports/Bilty';
import ChallanReport from './components/Reports/Challan';
import PageNotFound from './components/Errors/PageNotFound';
import ForbiddenPage from './components/Errors/ForbiddenPage';
import InvoicePrint from './components/Print/Invoice';
import InvoiceBilty from './components/Print/Bilty';
import InvoiceChallan from './components/Print/Challan';
import UserManage from './components/Users/User';


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
        path: '/invoice',
        name: 'invoice',
        component: InvoicePrint,
        meta: {
            auth: true
        }
    },
    {
        path: '/invoice/bilty',
        name: 'invoice.bilty',
        component: InvoiceBilty,
        meta: {
            auth: true
        }
    },
    {
        path: '/invoice/challan',
        name: 'invoice.challan',
        component: InvoiceChallan,
        meta: {
            auth: true
        }
    },
    //OPERATOR
    {
        path: '/operator',
        component: OperatorDashboard,
        meta: {
            auth: { roles: [1, 3, 4], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
        },
        children: [
            {
                path: '',
                name: 'dashboard',
                component: AdminDashboardStats,
            },
            {
                path: 'bilty/create',
                name: 'operator.bilty.create',
                component: AddBilty,
                meta: {
                    auth: { roles: [1, 3], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
                },
            },
            {
                path: 'bilty/manage',
                name: 'operator.bilty.manage',
                component: ManageBilty,
            },
            {
                path: 'bilty/receive',
                name: 'operator.bilty.receive',
                component: ReceiveBilty,
            },
            {
                path: 'bilty/monthly',
                name: 'operator.bilty.monthly',
                component: MonthlyBilty,
                meta: {
                    auth: { roles: [1, 3], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
                },
            },
            {
                path: 'bilty/edit/:id',
                name: 'operator.bilty.edit',
                component: EditBilty,
                meta: {
                    auth: { roles: [1, 3], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
                },
            },
            {
                path: 'ledger/manage',
                name: 'operator.ledger.manage',
                component: ManageLedger,
            },
            {
                path: 'ledger/edit/:id',
                name: 'operator.ledger.edit',
                component: EditLedger,
                meta: {
                    auth: { roles: [1, 3], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
                },
            },
            {
                path: 'account/customer',
                name: 'operator.account.customer',
                component: CustomerAccount,
            },
            {
                path: 'challan/create',
                name: 'operator.challan.create',
                component: AddChallan,
                meta: {
                    auth: { roles: [1, 3], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
                },
            },
            {
                path: 'challan/manage',
                name: 'operator.challan.manage',
                component: ManageChallan
            },
            {
                path: 'challan/edit/:id',
                name: 'operator.challan.edit',
                component: EditChallan,
                meta: {
                    auth: { roles: [1, 3], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
                },
            },
            {
                path: 'customer/create',
                name: 'operator.customer.create',
                component: AddCustomer,
                meta: {
                    auth: { roles: [1, 3], redirect: { name: 'login' }, forbiddenRedirect: '/403' }
                },
            },
            {
                path: 'customer/manage',
                name: 'operator.customer.manage',
                component: ManageCustomer
            },
            {
                path: 'customer/sender',
                name: 'operator.customer.sender',
                component: Sender
            },
            {
                path: 'customer/receiver',
                name: 'operator.customer.receiver',
                component: Receiver
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
                path: 'bilty/receive',
                name: 'admin.bilty.receive',
                component: ReceiveBilty,
            },
            {
                path: 'bilty/monthly',
                name: 'admin.bilty.monthly',
                component: MonthlyBilty,
            },
            {
                path: 'bilty/edit/:id',
                name: 'admin.bilty.edit',
                component: EditBilty,
            },
            //FURTHUR WORK ON AUTHENTICATION REQUIRED
            {
                path: 'user/manage',
                name: 'admin.user.manage',
                component: UserManage,
            },
            {
                path: 'ledger/manage',
                name: 'admin.ledger.manage',
                component: ManageLedger,
            },
            {
                path: 'ledger/edit/:id',
                name: 'admin.ledger.edit',
                component: EditLedger,
            },
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
                path: 'customer/sender',
                name: 'admin.customer.sender',
                component: Sender
            },
            {
                path: 'customer/receiver',
                name: 'admin.customer.receiver',
                component: Receiver
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