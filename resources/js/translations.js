export default {
    ka: {
        days: 'დღე',
        day: 'დღე',
        yes: 'დიახ',
        no: 'არა',
        dates_selected: 'თარიღი',
        toggleCamera: 'ბილეთის სკანირება',
        scanAgain: 'ხელახლა სკანირება',
        turnOffCamera: 'გამოსვლა',
        showMore: 'მეტის ჩვენება',
        cancel: 'გაუქმება',
        set: 'დაყენება',
        no_results: 'მონაცემები ვერ მოიძებნა',
        delete: 'წაშლა',
        active: 'აქტიური',
        activate: 'გააქტიურება',
        newRow: 'ახალი რიგი',
        details: 'დეტალურად',
        beforeDeparture: 'გასვლამდე დარჩენილია',
        routeNumber: 'მარშრუტის ნომერი',
        buyTickets: 'ბილეთების შეძენა',
        weekDays: [
            {value: 'Monday', text: 'ორშაბათი'},
            {value: 'Tuesday', text: 'სამშაბათი'},
            {value: 'Wednesday', text: 'ოთხშაბათი'},
            {value: 'Thursday', text: 'ხუთშაბათი'},
            {value: 'Friday', text: 'პარასკევი'},
            {value: 'Saturday', text: 'შაბათი'},
            {value: 'Sunday', text: 'კვირა'}
        ],
        routeDurationDays: [
            {value: 0, text: '0 დღე'},
            {value: 1, text: '1 დღე'},
            {value: 2, text: '2 დღე'},
            {value: 3, text: '3 დღე'},
            {value: 4, text: '4 დღე'},
            {value: 5, text: '5 დღე'}
        ],
        stoppingTimes: [
            {value: '00:00', text: 'გაჩერების გარეშე'},
            {value: '00:15', text: '15 წუთი'},
            {value: '00:30', text: '30 წუთი'},
            {value: '00:45', text: '45 წუთი'},
            {value: '01:00', text: '1 საათი'},
            {value: '01:15', text: '1 საათი 15 წუთი'},
            {value: '01:30', text: '1 საათი 30 წუთი'},
            {value: '01:45', text: '1 საათი 45 წუთი'},
            {value: '02:00', text: '2 საათი'}
        ],
        routeRating: {
            title: 'შეაფასეთ მგზავრობა',
        },
        profile: {
            title: 'პერსონალური ინფორმაცია',
            saveButton: 'დამახსოვრება',
            avatar: 'ავატარი',
            fields: {
                placeholders: {
                    name: 'სახელი, გვარი',
                    phone_number: '555 12 34 56',
                    id_number: 'პირადობის ნომერი',
                    email: 'ელექტრონული ფოსტა',
                    country: 'ქვეყანა',
                    city: 'ქალაქი',
                    birth_date: 'დაბადების თარიღი',
                    gender: 'სქესი'
                }
            },
            genders: [
                {value: 1, text: 'მამრობითი'},
                {value: 2, text: 'მდედრობითი'}
            ]
        },
        changePassword: {
            title: 'პაროლის შეცვლა',
            saveButton: 'დამახსოვრება',
            fields: {
                placeholders: {
                    oldPassword: 'არსებული პაროლი',
                    newPassword: 'ახალი პაროლი',
                    repeatNewPassword: 'გაიმეორეთ ახალი პაროლი'
                }
            }
        },
        driversLicense: {
            title: 'მართვის მოწმობა',
            saveButton: 'დამახსოვრება',
            fields: {
                labels: {
                    license_number: 'მართვის მოწმობის ნომერი',
                    front_side: 'წინა მხარე',
                    back_side: 'უკანა მხარე'
                }
            }
        },
        dates: {
            1: {
                long: 'იანვარი',
                short: 'იან'
            },
            2: {
                long: 'თებერვალი',
                short: 'თებ'
            },
            3: {
                long: 'მარტი',
                short: 'მარ'
            },
            4: {
                long: 'აპრილი',
                short: 'აპრ'
            },
            5: {
                long: 'მაისი',
                short: 'მაი'
            },
            6: {
                long: 'ივნისი',
                short: 'ივნ'
            },
            7: {
                long: 'ივლისი',
                short: 'ივლ'
            },
            8: {
                long: 'აგვისტო',
                short: 'აგვ'
            },
            9: {
                long: 'სექტემბერი',
                short: 'სექ'
            },
            10: {
                long: 'ოქტომბერი',
                short: 'ოქტ'
            },
            11: {
                long: 'ნოემბერი',
                short: 'ნოე'
            },
            12: {
                long: 'დეკემბერი',
                short: 'დეკ'
            }
        },
        settings: {
            title: 'პროფილი',
            caption: 'პირადი სივრცე',
            blocks: {
                profile: 'პერსონალური ინფო.',
                profile_sub: 'თქვენი პერსონალური ინფორმაცია',
                password: 'პაროლის შეცვლა',
                password_sub: 'შეცვალეთ პაროლი',
                financial: 'ფინანსური პარამეტრები',
                financial_sub: 'თანხის გამოთხოვის მეთოდები',
                driversLicense: 'მართვის მოწმობა',
                driversLicense_sub: 'დაამატე მართვის მოწმობა'
            }
        },
        home: {
            title: 'მთავარი',
            caption: 'პირადი სივრცე',
            boxes: {
                profile: 'პროფილი',
                profile_sub: 'მართე შენი პროფილი',
                vehicles: 'ტრანსპორტი',
                vehicles_sub: 'თქვენი ტრანსპორტები',
                routes: 'მარშრტუტები',
                routes_sub: 'მართეთ მარშრუტები',
                sales: 'გაყიდვები',
                sales_sub: 'ფინანსური ინფორმაცია',
                scanner: 'სკანერი',
                scanner_sub: 'ბილეთების შემოწმება',
                partners: 'პარტნიორები',
                partners_sub: 'მიიღე შემოსავალი',
                help: 'დახმარება',
                help_sub: 'ხშირი კითხვები',
                new_partner: 'ახალი პარტნიორი',
                new_partner_sub: 'რეგისტრაცია',
                partners_partners: 'პარტნიორები',
                partners_partners_sub: 'პარტნიორთა სია',
                partner_sales: 'გაყიდვები',
                partner_sales_sub: 'გაყიდვების ისტორია',
                partner_withdrawal: 'გამოთხოვა',
                partner_withdrawal_sub: 'თანხის გამოთხოვა',
                become_driver: 'გახდი მძღოლი',
                become_driver_sub: 'რეგისტრაცია',
                become_partner: 'გახდი პარტნიორი',
                become_partner_sub: 'რეგისტრაცია',
                ticketList: 'ბილეთები',
                ticketList_sub: 'შეძენილი ბილეთები',
            }
        },
        loginOrSignUp: {
            login: 'შესვლა',
            signUp: 'რეგისტრაცია'
        },
        menuItems: [
            'მთავარი', 'სკანერი', 'დახმარება', 'შეტყობინებები', 'გამოსვლა'
        ],
        login: {
            title: 'შესვლა',
            button: 'შესვლა',
            fields: {
                placeholders: {
                    phone_number: '555 12 34 56',
                    password: 'პაროლი'
                }
            },
            forgot_password: 'დაგავიწყდათ პაროლი?'
        },
        signup: {
            title: 'რეგისტრაცია',
            button: 'რეგისტრაცია',
            fields: {
                placeholders: {
                    phone_number: '555 12 34 56',
                    password: 'პაროლი',
                    affiliate_code: 'საპარტნიორო კოდი'
                }
            }
        },
        forgot: {
            title: 'დაგავიწყდათ პაროლი?',
            title_sub: 'შეიყვანეთ მობილური ტელეფონის ნომერი და მიიღეთ ახალი პაროლი',
            button: 'გაგზავნა',
            fields: {
                placeholders: {
                    phone_number: '555 12 34 56'
                }
            }
        },
        languages: {
            title: 'ენის ცვლილება'
        },
        financial: {
            title: 'ფინანსური პარამეტრები',
            titleBank: 'საბანკო ტრანსფერი',
            titleCard: 'საკრედიტო ბარათი',
            addButton: 'ახალი ანგარიშის დამატება',
            saveButton: 'დამახსოვრება',
            caption: 'პირადი სივრცე',
            caption2: 'აირჩიე თანხის გამოთხოვის მეთოდი',
            credit_card: 'საკრედიტო ბარათი',
            bank: 'საბანკო გადარიცხვა',
            primary: 'როგორც ძირითადი',
            paypal: 'PayPal',
        },
        paypal: {
            title: 'PayPal',
            addInfo: 'შეიყვანეთ ინფორმაცია',
            fields: {
                placeholders: {
                    email: 'PayPal ელ-ფოსტა'
                }
            },
            button: 'დამატება'
        },
        bank: {
            title: 'საბანკო პარამეტრები',
            caption2: 'საბანკო ინფორმაცია',
            fields: {
                placeholders: {
                    name: 'სახელი, გვარი'
                },
                labels: {
                    name: 'ბენეფიციარი',
                    bank: 'ბანკის სახელი',
                    iban: 'ანგარიშის ნომერი (IBAN)',
                    swift: 'SWIFT'
                }
            }
        },
        vehicles: {
            title: 'ტრანსპორტი',
            caption: 'მძღოლის სივრცე',
            blocks: {
                vehicleAdd: 'ტრანსპორტის დამატება',
                vehicleAdd_sub: 'დაარეგისტრირე ტრანსპორტი',
                vehiclesList: 'რეგისტრირებული ტრანსპორტი',
                vehiclesList_sub: 'რეგისტრირებული ტრანსპორტის სია',
            }
        },
        routes: {
            title: 'მარშრუტი',
            caption: 'მძღოლის სივრცე',
            blocks: {
                routeAdd: 'მარშრუტის რეგისტრაცია',
                routeAdd_sub: 'დაარეგისტრირე ახალი მარშრუტი',
                routesList: 'რეგისტრირებული მარშრუტები',
                routesList_sub: 'რეგისტრირებული მარშრუტების სია'
            }
        },
        QRScanner: {
            title: 'ბილეთის სკანერი',
            caption: 'შეამოწმეთ ბილეთი QR კოდით ან ბილეთის ნომრით',
            fields: {
                placeholders: {
                    ticketNumber: 'ბილეთის ნომერი'
                }
            },
            saveButton: 'ვალიდაცია'
        },
        vehicleAdd: {
            title: 'ტრანსპორტის რეგისტრაცია',
            saveButton: 'რეგისტრაცია',
            pleaseAddFrontAndBackText: 'გთხოვთ ატვირთოთ ავტომობილის რეგისტრაციის მოწმობა',
            fields: {
                labels: {
                    type: 'ტრანსპორტის (სერვისის) ტიპი',
                    manufacturer: 'მწარმოებელი',
                    model: 'მოდელი',
                    registration_country: 'რეგისტრაციის ქვეყანა',
                    license_number: 'სახ. ნომერი',
                    fuel_type: 'საწვავი',
                    year: 'გამოშვების წელი',
                    date_of_registration: 'რეგისტრაციის თარიღი',
                    number_of_seats: 'ადგილების რაოდენობა',
                    front_side: 'წინა მხარე',
                    back_side: 'უკანა მხარე'
                }
            }
        },
        vehicleEdit: {
            title: 'ტრანსპორტის მოდიფიკაცია',
            saveButton: 'დამახსოვრება'
        },
        vehicleList: {
            title: 'რეგისტრირებული ტრანსპორტი',
            change: 'შესვლა'
        },
        routeAdd: {
            title: 'მარშრუტის რეგისტრაცია',
            saveButton: 'რეგისტრაცია',
            caption2: 'ახალი მარშრუტის რეგისტრაცია',
            fields: {
                labels: {
                    route_type: 'ტრანსპორტის (სერვისის) ტიპი',
                    vehicle: 'ავტომობილი',
                    model: 'მოდელი',
                    license_number: 'სახ. ნომერი',
                    from: 'გასვლის ქალაქი',
                    to: 'ჩასვლის ქალაქი',
                    type: 'მარშრუტის ტიპი',
                    departure_day: 'გამგზავრების დღე',
                    arrival_day: 'ჩასვლის დღე',
                    departure_date: 'გამგზავრების თარიღი',
                    departure_dates: 'გამგზავრების თარიღები',
                    arrival_date: 'ჩასვლის თარიღი',
                    route_duration: 'მგზავრობის ხანგრძლივობა (საათი:წუთი)',
                    departure_time: 'გასვლის დრო',
                    arrival_time: 'ჩასვლის დრო',
                    stoppage_time: 'გაჩერების ხანგრძლივობა',
                    currency: 'ვალუტა',
                    price: 'ფასი',
                    departure_address: 'გასვლის მისამართი',
                    arrival_address: 'ჩასვლის მისამართი'
                }
            },
            routeTypes: [
                {value: 1, text: 'ერთჯერადი'},
                {value: 2, text: 'მრავალჯერადი'}
            ]
        },
        routesList: {
            title: 'მარშრუტების სია',
            vehicle: 'ტრანსპორტი',
            route: 'მარშრუტი',
            model: 'მოდელი',
            arrival_date: 'ჩასვლის თარიღი',
            departure_date: 'გასვლის თარიღი',
            departure_city: 'გასვლის ქალაქი',
            arrival_city: 'ჩასვლის ქალაქი',
            departure_time: 'დრო',
            arrival_time: 'დრო',
            type: [
                {id: 1, textBefore: 'მიკრო', textAfter: 'ავტობუსი'},
                {id: 2, textBefore: 'ავტო', textAfter: 'ბუსი'},
                {id: 3, textBefore: 'დამე', textAfter: 'მგზავრე'}
            ]
        },
        routeEdit: {
            title: 'მარშრუტის მოდიფიკაცია',
            saveButton: 'დამახსოვრება',
            deleteButton: 'წაშლა',
            caption2: 'არსებული მარშრუტის მოდიფიკაცია'
        },
        faqs: {
            title: 'დახმარება',
            addButton: 'დაგვიკავშირდით'
        },
        notifications: {
            title: 'შეტყობინებები',
            view: 'იხილეთ'
        },
        supportTicket: {
            title: 'მიმოწერა',
            support: 'ტექნიკური მხარდაჭერა'
        },
        newTicket: {
            title: 'ახალი დახმარების ბილეთი'
        },
        wizard: {
            next: 'შემდეგი',
            finish: 'დასრულება',
            back: 'უკან',
            step: 'ეტაპი',
            complete: {
                title: 'თქვენ წარმატებით გაიარეთ სწრაფი რეგისტრაცია',
                text: 'თქვენს მიერ შევსებული მონაცემები წარმატებით გამოიგზავნა ჩვენთან შესამოწმებლად. მონაცემების ვერიფიკაციას დაახლოებით 24 საათი დასჭირდება',
                goHome: 'პროფილის გვერდზე გადასვლა'
            }
        },
        driverArea: {
            title: 'მძღოლის სივრცე',
            caption2: 'გაყიდვების ისტორია, მართე შენი გაყიდვები',
            becomeDriver: 'გახდი მძღოლი, დაარეგისტრირე ავტომობილი და გაყიდე ბილეთები',
            becomeDriverButton: 'გახდი მძღოლი',
            blocks: {
                currentSales: 'მიმდინარე გაყიდვები',
                currentSales_sub: 'დაგეგმილი მარშრუტების გაყიდვები',
                salesByRoute: 'გაყიდვები მარშრუტების მიხედვით',
                salesByRoute_sub: 'ჯამური გაყიდვები და მოგება',
                salesHistory: 'გაყიდვების ისტორია',
                salesHistory_sub: 'ყველა გაყიდული ბილეთი',
                fines: 'ჯარიმები',
                fines_sub: 'გაუქმებული მარშრუტები',
                withdrawal: 'თანხის გამოთხოვა',
                withdrawal_sub: 'გამოითხოვეთ თანხა ნებისმიერ დროს'
            }
        },
        partnerArea: {
            title: 'პარტნიორის სივრცე',
            caption2: 'პარტნიორთა სია, გაყიდვები, საპარტნიორო კოდი',
            becomePartner: 'გაიაქტიურე პარტნიორის ანგარიში უფასოდ და მიიღე 0.7% და 0.3% - იანი შემოსავალი შენს მიერ მოწვეული ახალი პარტნიორის (მძღოლების) ყოველი გაყიდული ბილეთიდან',
            becomePartnerButton: 'გახდი პარტნიორი',
            blocks: {
                register: 'დაარეგისტრირეთ პარტნიორი',
                register_sub: 'დაირეგისტრირეთ თქვენი პარტნიორები',
                sales: 'გაყიდვები',
                sales_sub: 'მარშრუტების გაყიდვები',
                list: 'პარტნიორთა სია',
                list_sub: 'თქვენი პარტნიორებისა და მძღოლების სია',
                withdrawal: 'თანხის გამოთხოვა',
                withdrawal_sub: 'გამოითხოვეთ თანხა ნებისმიერ დროს'
            }
        },
        withdrawal: {
            title: 'თანხის გამოთხოვა / ისტორია',
            balance: 'ბალანსი',
            history: 'გამოთხოვის ისტორია',
            amount: 'შეიყვანეთ თანხის ოდენობა',
            total_requested: 'ჯამში მოთხოვნილი',
            total_withdrawn: 'ჯამში გატანილი',
            button: 'გამოთხოვა',
            withdraw_title: 'თანხის გამოთხოვა',
            no_sales: 'თანხის გამოთხოვა შეუძლებელია',
            status: [
                {
                    id: 1,
                    text: 'წარმატებული',
                    class: 'success--text'
                },
                {
                    id: 2,
                    text: 'მოთხოვნილი',
                    class: 'primary--text'
                },
                {
                    id: 3,
                    text: 'უარყოფილი',
                    class: 'error--text'
                }
            ]
        },
        salesHistory: {
            title: 'გაყიდვების ისტორია',
            total_sold: 'გაყიდული ბილეთები',
            total_sold_price_approved: 'ჯამში დადასტურებული',
            total_sold_price_unapproved: 'ჯამში დაუდასტურებელი',
            history: 'გაყიდვების სია',
            no_sales: 'გაყიდვების სია ცარიელია',
            passenger: 'მგზავრი',
            purchase_date: 'შეძენის თარიღი',
            departure: 'გამგზავრების ქალაქი',
            date: 'თარიღი',
            departure_date: 'თარიღი',
            departure_time: 'დრო',
            arrival: 'ჩასვლის ქალაქი',
            arrival_time: 'დრო',
            seat: 'ადგილი',
            status_text: 'სტატუსი',
            status: [
                {
                    id: 3,
                    text: 'გატარებული',
                    class: 'success--text'
                },
                {
                    id: 4,
                    text: 'დაბრუნებული',
                    class: 'error--text'
                },
                {
                    id: 1,
                    text: 'შეძენილი',
                    class: 'primary--text'
                },
                {
                    id: 5,
                    text: 'eCheck',
                    class: 'primary--text'
                },
                {
                    id: 6,
                    text: 'დაუდასტურებელი',
                    class: 'primary--text'
                }
            ]
        },
        salesByRoute: {
            title: 'მიმდინარე გაყიდვები',
            total_sold: 'ჯამში გაყიდული',
            no_sales: 'გაყიდვების სია ცარიელია',
            route: 'მარშრუტი',
            details: 'იხ. მარშრუტი'
        },
        partnerSales: {
            title: 'პარტნიორის გაყიდვები',
            total_sold: 'ჯამში გაყიდული',
            no_sales: 'გაყიდვების ისტორია ცარიელია'
        },
        partnerList: {
            title: 'პარტნიორთა სია',
            total: 'ჯამში',
            no_records: 'პარტნიორთა სია ცარიელია'
        },
        partnerDetails: {
            title: 'პარტნიორის დეტალები',
            fields: {
                name: 'სახელი, გვარი',
                email: 'ელ-ფოსტა',
                phone_number: 'მობილურის ნომერი'
            }
        },
        partnerRegister: {
            title: 'რეგისტრაცია',
            accountTypes: [
                {value: 'passenger', text: 'მგზავრი (პარტნიორი)'},
                {value: 'driver', text: 'მძღოლი (პარტნიორი)'},
            ],
            saveButton: 'რეგისტრაცია',
            fields: {
                placeholders: {
                    accountType: 'ანგარიშის ტიპი'
                }
            }
        },
        salesListByRoute: {
            title: 'გაყიდული ბილეთები'
        },
        driverSalesRouteInfo: {
            total_sold_currency: 'ჯამში გაყიდული',
            total_sold: 'გაყიდული ბილეთები',
            vehicle: 'ტრანსპორტი',
            route: 'მარშრუტი',
            departure_date: 'თარიღი',
            departure: 'გასვლის ადგილი',
            arrival: 'ჩასვლის ადგილი',
            departure_time: 'დრო',
            arrival_time: 'დრო'
        },
        routePreserve: {
            free: 'თავისუფალი',
            taken: 'შეძენილი',
            takenByDriver: 'დაკავებული მძღოლის მიერ',
            selectedByDriver: 'მონიშნული მძღოლის მიერ',
            saveButton: 'დაჯავშნა',
            deleteButton: 'წაშლა',
            cancelButton: 'გაუქმება'
        },
        driverFines: {
            title: 'ჯარიმები',
            total_fined: 'ჯამური ჯარიმის თანხა',
            no_fines: 'ჯარიმების ისტორია ცარიელია'
        },
        singleTicket: {
            title: 'თქვენი ბილეთი',
            cancel: 'ბილეთის გაუქმება',
        },
        ticketList: {
            title: 'შეძენილი ბილეთები',
            details: 'იხ. ბილეთი',
            no_data: 'შეძენილი ბილეთები ვერ მოიძებნა'
        }
    },
    en: {
        days: 'days',
        day: 'day',
        yes: 'Yes',
        no: 'No',
        dates_selected: 'date(s) selected',
        toggleCamera: 'Scan ticket',
        scanAgain: 'Scan again',
        showMore: 'Show more',
        no_results: 'No data',
        delete: 'Delete',
        active: 'Active',
        activate: 'Activate',
        newRow: 'New row',
        cancel: 'Cancel',
        set: 'Save',
        details: 'details',
        buyTickets: 'Buy tickets',
        beforeDeparture: 'Countdown to departure',
        routeNumber: 'Route Number',
        weekDays: [
            {value: 'Monday', text: 'Monday'},
            {value: 'Tuesday', text: 'Tuesday'},
            {value: 'Wednesday', text: 'Wednesday'},
            {value: 'Thursday', text: 'Thursday'},
            {value: 'Friday', text: 'Friday'},
            {value: 'Saturday', text: 'Saturday'},
            {value: 'Sunday', text: 'Sunday'}
        ],
        routeDurationDays: [
            {value: 0, text: '0 days'},
            {value: 1, text: '1 day'},
            {value: 2, text: '2 days'},
            {value: 3, text: '3 days'},
            {value: 4, text: '4 days'},
            {value: 5, text: '5 days'}
        ],
        stoppingTimes: [
            {value: '00:00', text: 'No stoppage time'},
            {value: '00:15', text: '15 minutes'},
            {value: '00:30', text: '30 minutes'},
            {value: '00:45', text: '45 minutes'},
            {value: '01:00', text: '1 hour'},
            {value: '01:15', text: '1 hour 15 minutes'},
            {value: '01:30', text: '1 hour 30 minutes'},
            {value: '01:45', text: '1 hour 45 minutes'},
            {value: '02:00', text: '2 hours'}
        ],
        profile: {
            title: 'Personal information',
            saveButton: 'Save',
            avatar: 'Adjust avatar',
            fields: {
                placeholders: {
                    name: 'Name, Last name',
                    phone_number: '555 12 34 56',
                    id_number: 'ID Number',
                    email: 'Email address',
                    country: 'Country',
                    city: 'City',
                    birth_date: 'Birth date',
                    gender: 'Gender'
                }
            },
            genders: [
                {value: 1, text: 'Male'},
                {value: 2, text: 'Female'}
            ]
        },
        home: {
            title: 'Home Page',
            caption: 'PERSONAL SPACE',
            boxes: {
                profile: 'Settings / Profile',
                profile_sub: 'Manage your profile',
                vehicles: 'Vehicles',
                vehicles_sub: 'Manage Vehicles',
                routes: 'Routes',
                routes_sub: 'Manage Routes',
                sales: 'Sales information',
                sales_sub: 'Manage ticket sales',
                scanner: 'QR Code Scanner',
                scanner_sub: 'Check tickets',
                partners: 'Partner Account',
                partners_sub: 'Find partner, earn 10%',
                help: 'Help / Support',
                help_sub: 'FAQ & Support',
                new_partner: 'New Partner',
                new_partner_sub: 'Registration',
                partners_partners: 'Partners',
                partners_partners_sub: 'Partners list',
                partner_sales: 'Sales',
                partner_sales_sub: 'Sale History',
                partner_withdrawal: 'Payout',
                partner_withdrawal_sub: 'Withdraw balance',
                become_driver: 'Become a driver',
                become_driver_sub: 'Registration',
                become_partner: 'Become a partner',
                become_partner_sub: 'Registration',
                ticketList: 'Tickets',
                ticketList_sub: 'Bought tickets',
            }
        },
        settings: {
            title: 'Profile',
            caption: 'Your personal space',
            blocks: {
                profile: 'Personal information',
                profile_sub: 'Your personal information',
                password: 'Change password',
                password_sub: 'Modify your password',
                financial: 'Financial parameters',
                financial_sub: 'Payout methods',
                driversLicense: 'Drivers license',
                driversLicense_sub: 'Add driver\'s license'
            }
        },
        dates: {
            1: {
                long: 'January',
                short: 'Jan'
            },
            2: {
                long: 'February',
                short: 'Feb'
            },
            3: {
                long: 'March',
                short: 'Mar'
            },
            4: {
                long: 'April',
                short: 'Apr'
            },
            5: {
                long: 'May',
                short: 'May'
            },
            6: {
                long: 'June',
                short: 'Jun'
            },
            7: {
                long: 'July',
                short: 'Jul'
            },
            8: {
                long: 'August',
                short: 'Aug'
            },
            9: {
                long: 'September',
                short: 'Sep'
            },
            10: {
                long: 'October',
                short: 'Oct'
            },
            11: {
                long: 'November',
                short: 'Nov'
            },
            12: {
                long: 'December',
                short: 'Dec'
            }
        },
        changePassword: {
            title: 'Change password',
            saveButton: 'Save',
            fields: {
                placeholders: {
                    oldPassword: 'Current password',
                    newPassword: 'New password',
                    repeatNewPassword: 'Repeat new password'
                }
            }
        },
        driversLicense: {
            title: 'Driver\'s license',
            saveButton: 'Save',
            fields: {
                labels: {
                    license_number: 'Driver\'s license number',
                    front_side: 'Front side',
                    back_side: 'Back side'
                }
            }
        },
        loginOrSignUp: {
            login: 'Login',
            signUp: 'Sign Up'
        },
        menuItems: [
            'Home', 'QR Scanner', 'FAQ', 'Notifications', 'Logout'
        ],
        login: {
            title: 'Login',
            button: 'Login',
            fields: {
                placeholders: {
                    phone_number: '555 12 34 56',
                    password: 'Password'
                }
            },
            forgot_password: 'Forgot password?'
        },
        signup: {
            title: 'Sign Up',
            button: 'Register',
            fields: {
                placeholders: {
                    phone_number: '555 12 34 56',
                    password: 'Password'
                }
            }
        },
        languages: {
            title: 'Change Language'
        },
        financial: {
            title: 'Financial',
            titleBank: 'Bank transfer',
            titleCard: 'Credit card',
            addButton: 'Add new',
            saveButton: 'Save',
            caption: 'Private space',
            caption2: 'Payout methods',
            credit_card: 'Credit Card',
            bank: 'Bank Transfer',
            primary: 'Make as primary payout method',
            paypal: 'PayPal',
        },
        paypal: {
            title: 'PayPal',
            addInfo: 'Add information',
            fields: {
                placeholders: {
                    email: 'PayPal E-mail address'
                }
            },
            button: 'Add PayPal'
        },
        bank: {
            title: 'Bank',
            caption2: 'Bank information',
            fields: {
                placeholders: {
                    name: 'Name, last name'
                },
                labels: {
                    name: 'Beneficiary',
                    bank: 'Bank name',
                    iban: 'Account number (IBAN)',
                    swift: 'SWIFT'
                }
            }
        },
        vehicles: {
            title: 'Vehicles',
            caption: 'Drivers area',
            blocks: {
                vehicleAdd: 'Vehicle registration',
                vehicleAdd_sub: 'Add your vehicle here',
                vehiclesList: 'Registered vehicles',
                vehiclesList_sub: 'List of registered vehicles',
            }
        },
        routes: {
            title: 'Routes',
            caption: 'Drivers area',
            blocks: {
                routeAdd: 'Route registration',
                routeAdd_sub: 'Register your routes here',
                routesList: 'Registered routes',
                routesList_sub: 'List of registered routes'
            }
        },
        QRScanner: {
            title: 'Scan ticket',
            caption: 'Check the ticket by QR code or by number',
            fields: {
                placeholders: {
                    ticketNumber: 'Ticket number'
                }
            },
            saveButton: 'Validate'
        },
        routeRating: {
            title: 'Rate your ride',
        },
        vehicleAdd: {
            title: 'Vehicle registration',
            saveButton: 'Register',
            pleaseAddFrontAndBackText: 'Please upload your vehicle license front and back sides',
            fields: {
                labels: {
                    type: 'Vehicle (route) type',
                    manufacturer: 'Manufacturer',
                    model: 'Model',
                    registration_country: 'Registration country',
                    license_number: 'License plate',
                    fuel_type: 'Fuel type',
                    year: 'Year manufactured',
                    date_of_registration: 'Registration date',
                    number_of_seats: 'Number of seats',
                    front_side: 'Front side',
                    back_side: 'Back side'
                }
            }
        },
        vehicleEdit: {
            title: 'Vehicle edit',
            saveButton: 'Save'
        },
        vehicleList: {
            title: 'Registered vehicles',
            change: 'Edit'
        },
        routeAdd: {
            title: 'Route registration',
            saveButton: 'Register',
            caption2: 'Add new route',
            fields: {
                labels: {
                    route_type: 'Vehicle type',
                    vehicle: 'Choose your Vehicle',
                    license_number: 'License plate',
                    from: 'From',
                    to: 'To',
                    type: 'Route type',
                    departure_day: 'Departure day',
                    arrival_day: 'Arrival day',
                    departure_date: 'Departure date',
                    departure_dates: 'Departure dates',
                    arrival_date: 'Arrival date',
                    route_duration: 'Route duration (hours:minutes)',
                    departure_time: 'Departure time',
                    arrival_time: 'Arrival time',
                    stoppage_time: 'Stoppage duration',
                    currency: 'Currency',
                    price: 'Price',
                    departure_address: 'Departure address',
                    arrival_address: 'Arrival address'
                }
            },
            routeTypes: [
                {value: 1, text: 'Scheduled'},
                {value: 2, text: 'Multiple'}
            ]
        },
        routeEdit: {
            title: 'Editing Route',
            saveButton: 'Save',
            deleteButton: 'Delete',
            caption2: 'Editing existing route'
        },
        routesList: {
            title: 'Registered routes',
            vehicle: 'TRANSPORT TYPE',
            route: 'ROUTE ID',
            model: 'TRANSPORT',
            arrival_date: 'Arrival date',
            departure_date: 'DEPARTURE DAY',
            departure_city: 'DEPARTURE',
            arrival_city: 'ARRIVAL',
            departure_time: 'TIME',
            arrival_time: 'TIME',
            type: [
                {id: 1, textBefore: 'Mini', textAfter: 'bus'},
                {id: 2, textBefore: 'Bus', textAfter: ''},
                {id: 3, textBefore: 'Car', textAfter: 'Pooling'}
            ]
        },
        faqs: {
            title: 'FAQs/Help',
            addButton: 'New support ticket'
        },
        notifications: {
            title: 'Notifications',
            view: 'View'
        },
        supportTicket: {
            title: 'Support',
            support: 'Technical Support'
        },
        newTicket: {
            title: 'New Support Ticket'
        },
        wizard: {
            next: 'Next',
            finish: 'Finalize',
            back: 'Back',
            step: 'Step',
            complete: {
                title: 'You\'ve successfully completed the onboarding process',
                text: 'Every data you\'ve entered has been sent for review. It usually takes no more than 24 hours to verify the data.',
                goHome: 'Go to profile'
            }
        },
        driverArea: {
            title: 'Driver Area',
            caption2: 'Sales history, manage your finances',
            becomeDriver: 'Become a driver, add your vehicle, routes and sell tickets',
            becomeDriverButton: 'Join drivers program',
            blocks: {
                currentSales: 'Current sales',
                currentSales_sub: 'Sales of future planned routes',
                salesByRoute: 'Ongoing sales',
                salesByRoute_sub: 'Current sales data by route',
                salesHistory: 'Sales history',
                salesHistory_sub: 'All sold tickets',
                fines: 'Fines',
                fines_sub: 'Fines caused by cancelled routes',
                withdrawal: 'Withdrawal',
                withdrawal_sub: 'Withdraw your funds anytime'
            }
        },
        partnerArea: {
            title: 'Partners Area',
            caption2: 'Partners list, sales, affiliate code',
            becomePartner: 'Become a partner with 1 click and earn 0.7% and 0.3% from each sale',
            becomePartnerButton: 'Register in partner program',
            blocks: {
                code: 'Affiliate code',
                code_sub: 'Code to share',
                sales: 'Sales',
                sales_sub: 'Sales by your partners and drivers',
                list: 'Partner\'s list',
                list_sub: 'Your partner\'s and driver\'s list',
                withdrawal: 'Withdrawal',
                withdrawal_sub: 'Withdraw your funds anytime'
            }
        },
        withdrawal: {
            title: 'Withdrawal / Payouts',
            balance: 'Balance',
            history: 'History',
            amount: 'Amount',
            total_requested: 'Total requested',
            total_withdrawn: 'Total withdrawn',
            button: 'Request',
            withdraw_title: 'Withdrawal Request',
            no_sales: 'Withdrawal is unavailable',
            status: [
                {
                    id: 1,
                    text: 'Complete',
                    class: 'success--text'
                },
                {
                    id: 2,
                    text: 'Requested',
                    class: 'primary--text'
                },
                {
                    id: 3,
                    text: 'Declined',
                    class: 'error--text'
                }
            ]
        },
        salesHistory: {
            title: 'Sales History',
            total_sold: 'Sold tickets',
            total_sold_price_approved: 'Total amount approved',
            total_sold_price_unapproved: 'Total amount pending',
            history: 'History data',
            no_sales: 'Sales history is empty',
            passenger: 'Passenger',
            purchase_date: 'Date purchased',
            departure: 'Departure city',
            date: 'Date',
            departure_date: 'Date',
            departure_time: 'Time',
            arrival: 'Arrival city',
            arrival_time: 'Time',
            seat: 'Seat number',
            status_text: 'Status',
            status: [
                {
                    id: 3,
                    text: 'Parsed',
                    class: 'success--text'
                },
                {
                    id: 4,
                    text: 'Refunded',
                    class: 'error--text'
                },
                {
                    id: 1,
                    text: 'Bought',
                    class: 'primary--text'
                },
                {
                    id: 5,
                    text: 'eCheck',
                    class: 'primary--text'
                },
                {
                    id: 6,
                    text: 'Unverified',
                    class: 'primary--text'
                }
            ]
        },
        salesByRoute: {
            title: 'Current sales',
            total_sold: 'Total Sold',
            no_sales: 'Current sales is empty',
            route: 'Route',
            details: 'Detailed'
        },
        partnerSales: {
            title: 'Partner sales',
            total_sold: 'Total sold',
            no_sales: 'Sales history is empty'
        },
        partnerList: {
            title: 'Partners list',
            total: 'Total',
            no_records: 'Partners list is empty'
        },
        partnerDetails: {
            title: 'Partner details',
            fields: {
                name: 'Name',
                email: 'Email',
                phone_number: 'Phone number'
            }
        },
        partnerRegister: {
            title: 'Register your driver/partner',
            accountTypes: [
                {value: 'passenger', text: 'Passenger (Partner)'},
                {value: 'driver', text: 'Driver (Partner)'},
            ],
            saveButton: 'Register',
            fields: {
                placeholders: {
                    accountType: 'Account type'
                }
            }
        },
        salesListByRoute: {
            title: 'Sales'
        },
        driverSalesRouteInfo: {
            total_sold_currency: 'Total sold',
            total_sold: 'Sold tickets',
            vehicle: 'Vehicle',
            route: 'Route',
            departure_date: 'Departure',
            departure: 'Departure',
            arrival: 'Arrival',
            departure_time: 'Time',
            arrival_time: 'Time'
        },
        routePreserve: {
            free: 'Free',
            taken: 'Sold',
            takenByDriver: 'Taken by driver',
            selectedByDriver: 'Selected by driver',
            saveButton: 'Reserve',
            deleteButton: 'Delete',
            cancelButton: 'Cancel'
        },
        driverFines: {
            title: 'Fines',
            total_fined: 'Total fined',
            no_fines: 'Fines history is empty'
        },
        singleTicket: {
            title: 'Your ticket',
            cancel: 'Cancel ticket',
        },
        ticketList: {
            title: 'Bought tickets',
            details: 'Details',
            no_data: 'You haven\'t bought any tickets yet'
        }
    }
}
