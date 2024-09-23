import { siteURL } from './config'

export default [
    {
        name: 'loginForWeb',
        url: siteURL + '/auth/login',
    },
    {
        name: 'logoutForWeb',
        url: siteURL + '/auth/logout',
    },
    {
        name: 'getNotificationCount',
        url: siteURL + '/api/notificationCount',
        method: 'GET'
    },
    {
        name: 'profileUpdate',
        url: siteURL + '/api/profileUpdate',
        method: 'POST'
    },
    {
        name: 'profileGet',
        url: siteURL + '/api/profileGet',
        method: 'GET'
    },
    {
        name: 'login',
        url: siteURL + '/oauth/token',
        method: 'POST'
    },
    {
        name: 'signup',
        url: siteURL + '/api/registration',
        method: 'POST'
    },
    {
        name: 'signupAsPartner',
        url: siteURL + '/api/registration-partner',
        method: 'POST'
    },
    {
        name: 'forgot',
        url: siteURL + '/api/forgot',
        method: 'POST'
    },
    {
        name: 'refreshToken',
        url: siteURL + '/oauth/token',
        method: 'POST'
    },
    {
        name: 'changePassword',
        url: siteURL + '/api/changePassword',
        method: 'POST'
    },
    {
        name: 'financialPrimarySet',
        url: siteURL + '/api/financial/setPrimary',
        method: 'POST'
    },
    {
        name: 'financialPrimaryGet',
        url: siteURL + '/api/financial/getPrimary',
        method: 'GET'
    },
    {
        name: 'financialByTypeGet',
        url: siteURL + '/api/financial/getByType',
        method: 'POST'
    },
    {
        name: 'financialByTypeSet',
        url: siteURL + '/api/financial/setByType',
        method: 'POST'
    },
    {
        name: 'deleteFinancial',
        url: siteURL + '/api/financial/delete',
        method: 'POST'
    },
    {
        name: 'financialAdd',
        url: siteURL + '/api/financial/add',
        method: 'POST'
    },
    {
        name: 'driversLicense',
        url: siteURL + '/api/driver/license/add',
        method: 'POST'
    },
    {
        name: 'licenseGet',
        url: siteURL + '/api/driver/license/get',
        method: 'GET'
    },
    {
        name: 'getRouteCount',
        url: siteURL + '/api/driver/getRouteCount',
        method: 'GET'
    },
    {
        name: 'getVehicleCount',
        url: siteURL + '/api/driver/getVehicleCount',
        method: 'GET'
    },
    {
        name: 'vehicleConstructor',
        url: siteURL + '/api/driver/vehicle/constructor',
        method: 'GET'
    },
    {
        name: 'vehicleAdd',
        url: siteURL + '/api/driver/vehicle/add',
        method: 'POST'
    },
    {
        name: 'vehicleEdit',
        url: siteURL + '/api/driver/vehicle/edit',
        method: 'POST'
    },
    {
        name: 'getVehicleList',
        url: siteURL + '/api/driver/vehicle/list',
        method: 'GET'
    },
    {
        name: 'routeConstructor',
        url: siteURL + '/api/driver/route/constructor',
        method: 'GET'
    },
    {
        name: 'getVehiclesByRouteType',
        url: siteURL + '/api/driver/route/changeType',
        method: 'GET'
    },
    {
        name: 'getLicensesByVehicle',
        url: siteURL + '/api/driver/route/changeVehicle',
        method: 'GET'
    },
    {
        name: 'routeAdd',
        url: siteURL + '/api/driver/route/add',
        method: 'POST'
    },
    {
        name: 'routeEdit',
        url: siteURL + '/api/driver/route/edit',
        method: 'POST'
    },
    {
        name: 'searchCities',
        url: siteURL + '/api/cities/search',
        method: 'GET'
    },
    {
        name: 'searchAddresses',
        url: siteURL + '/api/addresses/search',
        method: 'GET'
    },
    {
        name: 'getRoutesList',
        url: siteURL + '/api/driver/route/list',
        method: 'GET'
    },
    {
        name: 'getFines',
        url: siteURL + '/api/driver/fines/get',
        method: 'GET'
    },
    {
        name: 'deleteRoute',
        url: siteURL + '/api/driver/route/delete',
        method: 'POST'
    },
    {
        name: 'cancelRoute',
        url: siteURL + '/api/driver/route/cancel',
        method: 'POST'
    },
    {
        name: 'getFAQ',
        url: siteURL + '/api/faq',
        method: 'GET'
    },
    {
        name: 'getNotificationsList',
        url: siteURL + '/api/notificationsList',
        method: 'GET'
    },
    {
        name: 'getTicketMessages',
        url: siteURL + '/api/tickets/get',
        method: 'GET'
    },
    {
        name: 'supportTicket',
        url: siteURL + '/api/tickets/new',
        method: 'POST'
    },
    {
        name: 'supportTicketReply',
        url: siteURL + '/api/tickets/reply',
        method: 'POST'
    },
    {
        name: 'getWizardData',
        url: siteURL + '/api/wizard/get',
        method: 'GET'
    },
    {
        name: 'wizardByStep',
        url: siteURL + '/api/wizard/getByStep',
        method: 'POST'
    },
    {
        name: 'getPayouts',
        url: siteURL + '/api/payouts/get',
        method: 'GET'
    },
    {
        name: 'payoutAdd',
        url: siteURL + '/api/payouts/add',
        method: 'POST'
    },
    {
        name: 'getPartnerCode',
        url: siteURL + '/api/partnerCode/get',
        method: 'GET'
    },
    {
        name: 'getSalesHistory',
        url: siteURL + '/api/salesHistory/get',
        method: 'GET'
    },
    {
        name: 'getSalesByRoute',
        url: siteURL + '/api/salesByRoute/get',
        method: 'GET'
    },
    {
        name: 'getPartnerSales',
        url: siteURL + '/api/partnerSales/get',
        method: 'GET'
    },
    {
        name: 'getPartnerList',
        url: siteURL + '/api/partnerList/get',
        method: 'GET'
    },
    {
        name: 'getPartnerDetails',
        url: siteURL + '/api/partnerDetails/get',
        method: 'GET'
    },
    {
        name: 'becomePartner',
        url: siteURL + '/api/partner/become',
        method: 'POST'
    },
    {
        name: 'becomeDriver',
        url: siteURL + '/api/driver/become',
        method: 'POST'
    },
    {
        name: 'preserveRoute',
        url: siteURL + '/api/driver/route/preserve',
        method: 'POST'
    },
    {
        name: 'parseQR',
        url: siteURL + '/api/parseQR',
        method: 'POST'
    },
    {
        name: 'parseTicket',
        url: siteURL + '/api/parseTicket',
        method: 'POST'
    },
    {
        name: 'decodeQR',
        url: siteURL + '/api/decodeQR',
        method: 'POST'
    },
    {
        name: 'getResumedWizard',
        url: siteURL + '/api/wizard/getResumed',
        method: 'GET'
    },
    {
        name: 'getTicketList',
        url: siteURL + '/api/ticket/list',
        method: 'GET',
    },
    {
        name: 'getTicket',
        url: siteURL + '/api/ticket/get',
        method: 'GET',
    },
    {
        name: 'getTicketSecure',
        url: siteURL + '/api/ticket/getSecure',
        method: 'GET',
    },
    {
        name: 'getCancelTicketModalText',
        url: siteURL + '/api/ticket/checkRefund',
        method: 'POST',
    },
    {
        name: 'performRefund',
        url: siteURL + '/api/ticket/refund',
        method: 'POST',
    },
    {
        name: 'setAvatar',
        url: siteURL + '/api/setAvatar',
        method: 'POST'
    },
    {
        name: 'setPreferredLocale',
        url: siteURL + '/api/setPreferredLocale',
        method: 'POST'
    },
    {
        name: 'getRouteRating',
        url: siteURL + '/api/routeRating/get',
        method: 'GET',
    },
    {
        name: 'rateRoute',
        url: siteURL + '/api/routeRating/rate',
        method: 'POST',
    }
]
