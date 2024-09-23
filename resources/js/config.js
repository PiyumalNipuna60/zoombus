export const siteURL = 'https://zoombus.net'
export const client = {
    id: 2,
    secret: '6INHr6KFa9stUNgmHfdDAk1azAk212qbCojOE7H3'
}

export function imagesPathRewrite(file) {
    return '/images/mobile/' + file
}

export function notificationTypesPath(file) {
    return '/images/notification-types/' + file
}

export const googleRecaptcha = {
    siteKey: '6LcstsMUAAAAAGp_J4ZaetoDdTUjiRK3WgVY9Px7'
}

export const vehiclesPerPage = 10
export const routesPerPage = 10
export const notificationsPerPage = 10
export const withdrawalsPerPage = 10
export const salesHistoryPerPage = 10
export const currentRouteSalesPerPage = 10
export const boughtTicketsPerPage = 10
export const routeSalesPerPage = 10
export const finesPerPage = 10
export const partnerSalesPerPage = 10
export const partnerListPerPage = 10
export const partnerListVehiclesPerPage = 10

export const vehicleSeats = [
    {
        id: 1,
        name: 'minibus',
        min: 7,
        max: 29,
        seat: {
            top: 276,
            height: 45,
            separator: 35
        },
        spliceIndex: 1,
        right: [216, 168, 120, 72],
        width: 47,
        rightPlus: (parseInt(window.innerWidth)/2)-187.5,
        firstMinus: 0
    },
    {
        id: 2,
        name: 'bus',
        min: 30,
        max: 70,
        seat: {
            top: 150,
            height: 45,
            separator: 5
        },
        spliceIndex: 2,
        right: [228, 188, 148, 108, 68],
        width: 39,
        rightPlus: (parseInt(window.innerWidth)/2)-187.5,
        firstMinus: 0
    },
    {
        id: 3,
        name: 'car',
        min: 4,
        max: 4,
        seat: {
            top: 98,
            height: 45,
            separator: 5
        },
        width: 50,
        rightPlus: (parseInt(window.innerWidth)/2)-187.5,
        firstMinus: 10
    }
]

export function addNewRow(routeType, values) {
    const configRoute = vehicleSeats.find(d => d.id === routeType)
    if (configRoute.max >= values.length) {
        const maxTop = values.map(d => {
            return d.top
        })

        if(routeType === 1) {
            if (
                values[values.length - 1].top === values[values.length - 2].top &&
                values[values.length - 2].top === values[values.length - 3].top &&
                values[values.length - 3].top === values[values.length - 4].top
            ) {
                for (let j = 1; j <= configRoute.spliceIndex; j++) {
                    if (values[values.length - j]) {
                        values[values.length - j].value = values[values.length - j].value - 1
                    }
                }
                values.splice(values.length - 2, 1)
            }
        }
        else if (routeType === 2) {
            if (
                values[values.length - 1].top === values[values.length - 2].top &&
                values[values.length - 2].top === values[values.length - 3].top &&
                values[values.length - 3].top === values[values.length - 4].top &&
                values[values.length - 4].top === values[values.length - 5].top
            ) {
                for (let j = 1; j <= configRoute.spliceIndex; j++) {
                    if (values[values.length - j]) {
                        values[values.length - j].value = values[values.length - j].value - 1
                    }
                }
                values.splice(values.length - 3, 1)
            }
        }

        const currentLength = values.length
        for (let k = 1; k <= configRoute.right.length; k++) {
            const right = configRoute.right[k - 1]
            values.push({
                value: currentLength + k,
                top: Math.max(...maxTop) + configRoute.seat.height + configRoute.seat.separator,
                right: right + configRoute.rightPlus
            })
        }


        return values
    }
}
