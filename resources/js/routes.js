import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from './views/Home'
import FAQ from './views/FAQ'
import Settings from './views/Settings'
import Profile from './views/profile/Profile'
import Password from './views/profile/changePassword'
import Financial from './views/profile/Financial'
import FinancialPayPal from './views/profile/financial/PayPalList'
import FinancialPayPalAdd from './views/profile/financial/PayPalAdd'
import FinancialBank from './views/profile/financial/BankAccount'
import DriversLicense from './views/profile/DriversLicense'
import Vehicles from './views/Vehicles'
import Routes from './views/Routes'
import VehicleAdd from './views/vehicles-routes/VehicleAdd'
import VehicleEdit from './views/vehicles-routes/VehicleEdit'
import VehiclesList from './views/vehicles-routes/VehicleList'
import RouteAdd from './views/vehicles-routes/RouteAdd'
import RouteEdit from './views/vehicles-routes/RouteEdit'

import Notifications from './views/Notifications'

import NewTicket from './views/newTicket'
import SupportTicket from './views/SupportTicket'

import Login from './views/Login'
import Signup from './views/Signup'
import Forgot from './views/Forgot'
import Languages from './views/Languages'

import {accessTokenRoles} from './vuex/store'
import RouteList from './views/vehicles-routes/RouteList'
import Wizard from './views/Wizard'
import DriverArea from './views/DriverArea'
import Withdrawal from './views/driver/Withdrawal'

import CurrentSales from './views/driver/CurrentSales'
import SalesByRoute from './views/driver/SalesByRoute'
import SalesHistory from './views/driver/SalesHistory'
import Fines from './views/driver/Fines'

import WithdrawalPartner from './views/partner/Withdrawal'
import PartnerArea from './views/PartnerArea'
import PartnerSales from './views/partner/PartnerSales'
import PartnerList from './views/partner/PartnersList'
import PartnerDetails from './views/partner/PartnerDetails'
import PartnerRegister from './views/partner/PartnerRegister'
import QRScanner from './views/QRScanner'
import SingleTicket from "./views/tickets/SingleTicket"
import TicketList from "./views/tickets/TicketList"
import SingleTicketSecure from "./views/tickets/SingleTicketSecure";
import RateRoute from "./views/RateRoute";

Vue.use(VueRouter)

const routes = []
routes.push(
    {
        path: '/main',
        name: 'home',
        meta: {
            requiresAuth: true
        },
        component: Home
    },
    {
        path: '/qrscanner',
        name: 'scanner',
        meta: {
            requiresAuth: true
        },
        component: QRScanner
    },
    {
        path: '/profile',
        name: 'profile',
        meta: {
            requiresAuth: true,
            parent: 'settings'
        },
        component: Profile
    },
    {
        path: '/edit-password',
        name: 'password',
        meta: {
            requiresAuth: true,
            parent: 'settings'
        },
        component: Password
    },
    {
        path: '/financial',
        name: 'financial',
        meta: {
            requiresAuth: true,
            parent: 'settings'
        },
        component: Financial
    },
    {
        path: '/financial/paypal',
        name: 'financialPayPal',
        meta: {
            requiresAuth: true,
            parent: 'financial'
        },
        component: FinancialPayPal
    },
    {
        path: '/financial/paypal/add',
        name: 'financialPayPalAdd',
        meta: {
            requiresAuth: true,
            parent: 'financialPayPal'
        },
        component: FinancialPayPalAdd
    },
    {
        path: '/financial/credit-card',
        name: 'financialCreditCard',
        meta: {
            requiresAuth: true,
            parent: 'financial'
        },
        component: FinancialPayPal
    },
    {
        path: '/financial/bank',
        name: 'financialBank',
        meta: {
            requiresAuth: true,
            parent: 'financial'
        },
        component: FinancialBank
    },
    {
        path: '/driver/license',
        name: 'driversLicense',
        meta: {
            requiresAuth: true,
            parent: 'settings'
        },
        component: DriversLicense
    },
    {
        path: '/settings',
        name: 'settings',
        meta: {
            requiresAuth: true,
            parent: 'home'
        },
        component: Settings
    },
    {
        path: '/login',
        name: 'login',
        meta: {
            requiresGuest: true
        },
        component: Login
    },
    {
        path: '/signup',
        name: 'signup',
        meta: {
            requiresGuest: true
        },
        component: Signup
    },
    {
        path: '/forgot-password',
        name: 'forgot',
        meta: {
            requiresGuest: true,
            parent: 'login'
        },
        component: Forgot
    },
    {
        path: '/languages',
        name: 'languages',
        component: Languages
    },
    {
        path: '/driver/vehicles',
        name: 'vehicles',
        component: Vehicles,
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'home'
        }
    },
    {
        path: '/faq',
        name: 'faq',
        component: FAQ
    },
    {
        path: '/driver/vehicle-registration',
        name: 'vehicleAdd',
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'vehicles'
        },
        component: VehicleAdd
    },
    {
        path: '/driver/vehicle/edit/:id',
        name: 'vehicleEdit',
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'vehiclesList'
        },
        component: VehicleEdit
    },
    {
        path: '/driver/vehicles/list',
        name: 'vehiclesList',
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'vehicles'
        },
        component: VehiclesList
    },
    {
        path: '/driver/route-registration',
        name: 'routeAdd',
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'routes'
        },
        component: RouteAdd
    },
    {
        path: '/driver/route/edit/:id',
        name: 'routeEdit',
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'routesList'
        },
        component: RouteEdit
    },
    {
        path: '/driver/routes',
        name: 'routes',
        component: Routes,
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'home'
        }
    },
    {
        path: '/driver/routes/list',
        name: 'routesList',
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'routes'
        },
        component: RouteList
    },
    {
        path: '/notifications',
        name: 'notifications',
        component: Notifications,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/support-secure/ticket/:id/:latest_message',
        name: 'support_ticket',
        component: SupportTicket,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/support',
        name: 'newTicket',
        component: NewTicket,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/driver/wizard',
        name: 'wizard',
        component: Wizard,
        meta: {
            requiresAuth: true,
            requiresDriver: true
        }
    },
    {
        path: '/drivers-area',
        name: 'driverArea',
        component: DriverArea,
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'home'
        }
    },
    {
        path: '/partners-area',
        name: 'partnerArea',
        component: PartnerArea,
        meta: {
            requiresAuth: true,
            requiresPartner: true,
            parent: 'home'
        }
    },

    {
        path: '/driver/current-sales',
        name: 'currentSales',
        component: CurrentSales,
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'driverArea'
        }
    },
    {
        path: '/driver/sales',
        name: 'salesByRoute',
        component: SalesByRoute,
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'driverArea'
        }
    },
    {
        path: '/driver/sales-history',
        name: 'salesHistory',
        component: SalesHistory,
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'driverArea'
        }
    },
    {
        path: '/driver/payouts',
        name: 'withdrawal',
        component: Withdrawal,
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'driverArea'
        }
    },
    {
        path: '/driver/fines',
        name: 'fines',
        component: Fines,
        meta: {
            requiresAuth: true,
            requiresDriver: true,
            parent: 'driverArea'
        }
    },

    {
        path: '/partner/code-generator',
        name: 'partnersRegister',
        component: PartnerRegister,
        meta: {
            requiresAuth: true,
            requiresPartner: true,
            parent: 'partnerArea'
        }
    },
    {
        path: '/partner/list',
        name: 'partnersList',
        component: PartnerList,
        meta: {
            requiresAuth: true,
            requiresPartner: true,
            parent: 'partnerArea'
        }
    },
    {
        path: '/partner/details/:user_id',
        name: 'partnerDetails',
        component: PartnerDetails,
        meta: {
            requiresAuth: true,
            requiresPartner: true,
            parent: 'partnersList'
        }
    },
    {
        path: '/partner/sales',
        name: 'partnerSales',
        component: PartnerSales,
        meta: {
            requiresAuth: true,
            requiresPartner: true,
            parent: 'partnerArea'
        }
    },
    {
        path: '/partner/payouts',
        name: 'withdrawalPartner',
        component: WithdrawalPartner,
        meta: {
            requiresAuth: true,
            requiresPartner: true,
            parent: 'partnerArea'
        }
    },
    {
        path: '/bought-tickets',
        name: 'ticketList',
        component: TicketList,
        meta: {
            requiresAuth: true,
            parent: 'home'
        }
    },
    {
        path: '/ticket/:id',
        name: 'singleTicket',
        component: SingleTicket,
        meta: {
            requiresAuth: true,
            parent: 'ticketList'
        }
    },
    {
        path: '/t/s/:id',
        name: 'singleTicketSecure',
        component: SingleTicketSecure,
        meta: {
            requiresAuth: true,
            parent: 'ticketList'
        }
    },
    {
        path: '/rate/:id',
        name: 'rateRoute',
        component: RateRoute,
        meta: {
            requiresAuth: true,
            parent: 'ticketList'
        }
    },
)

const Router = new VueRouter({
    mode: 'history',
    routes: routes
})

Router.beforeEach((to, from, next) => {
    window.scrollTo(0, 0)
    const automaticLoginResponse = (document.querySelector("meta[name='response']")) ? document.querySelector("meta[name='response']").getAttribute('content') : null
    const automaticLoginLocale = (document.querySelector("meta[name='locale']")) ? document.querySelector("meta[name='locale']").getAttribute('content') : null
    const loggedIn = localStorage.getItem('user') || automaticLoginResponse || false

    if(automaticLoginLocale) {
        localStorage.setItem('locale', automaticLoginLocale)
    }
    if(automaticLoginResponse) {
        localStorage.setItem('user', automaticLoginResponse)
    }

    if (to.matched.some(record => record.meta.requiresAuth) && !loggedIn) {
        next({name: 'login'})
    } else if (to.matched.some(record => record.meta.requiresGuest) && loggedIn) {
        next({name: 'home'})
    }

    const isRole = accessTokenRoles(loggedIn)
    if (to.matched.some(record => record.meta.requiresDriver) && !isRole.includes('driver')) {
        next({name: to.meta.parent})
    }
    if (to.matched.some(record => record.meta.requiresPartner) && !isRole.includes('partner')) {
        next({name: to.meta.parent})
    }

    next()
})

export default Router
