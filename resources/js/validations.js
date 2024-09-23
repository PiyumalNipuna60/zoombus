export default {
    ka: {
        profileUpdate: {
            email: {
                required: 'ელ-ფოსტის შევსება აუცილებელია',
                valid: 'ელ-ფოსტის ფორმატი არასწორია'
            },
            phone_number: {
                required: 'მობილურის შევსება აუცილებელია',
                valid: 'მობილური ნომრის ფორმატი არასწორია'
            },
            avatar: {
                imageSizeSmall: 'სურათის ზომები უნდა აღემატებოდეს 150x150 პიქსელს',
                success: 'ავატარი წარმატებით განახლდა',
                failed: 'სურათის ატვირთვა ვერ მოხერხდა',
            },
            name: {
                required: 'სახელის შევსება აუცილებელია'
            },
            gender: {
                required: 'სქესის მონიშვნა აუცილებელია'
            },
            id_number: {
                required: 'პირადი ნომრის შევსება აუცილებელია',
                valid: 'პირადი ნომერი არ არის ვალიდური'
            },
            city: {
                required: 'ქალაქის ველის შევსება აუცილებელია'
            },
            success: 'პროფილი წარმატებით განახლდა'
        },
        partnerRegister: {
            affiliateCode: {
                required: 'კოდის შეყვანა აუცილებელია'
            }
        },
        signupAsPartner: {
            success: 'რეგისტრაცია წარმატებით დასრულდა'
        },
        QRScanner: {
            ticket_number: {
                required: 'ბილეთის ნომრის შეყვანა აუცილებელია'
            },
            cameraErrors: {
                NotAllowedError: 'გთხოვთ დაადასტუროთ კამერის გამოყენების უფლება',
                NotFoundError: 'ამ მოწყობილობაზე კამერა არ მოიძებნა',
                NotSupportedError: 'HTTPS დაცვა ვერ მოიძებნა',
                NotReadableError: 'კამერა უკვე გამოყენებულია',
                OverconstrainedError: 'არსებული კამერა შეუსაბამოა',
                StreamApiNotSupportedError: 'გთხოვთ სცადოთ სხვა ბრაუზერი'
            },
            success: 'ბილეთი წარმატებით გატარდა'
        },
        login: {
            phone_number: {
                required: 'მობილურის შევსება აუცილებელია'
            },
            password: {
                required: 'პაროლის შევსება აუცილებელია'
            },
            invalid_grant: 'მობილურის ნომერი ან/და პაროლი არასწორია'
        },
        signup: {
            phone_number: {
                required: 'მობილურის შევსება აუცილებელია'
            },
            password: {
                required: 'პაროლის შევსება აუცილებელია'
            },
            success: 'რეგისტრაცია წარმატებით დასრულდა'
        },
        changePassword: {
            old_password: {
                required: 'არსებული პაროლის შეყვანა აუცილებელია'
            },
            password: {
                required: 'პაროლის შეყვანა აუცილებელია',
                match: 'პაროლები არ ემთხვევა ერთმანეთს'
            },
            success: 'პაროლი წარმატებით განახლდა'
        },
        forgot: {
            success: 'ახალი პაროლი წარმატებით გამოიგზავნა'
        },
        financialPrimarySet: {
            success: 'ფინანსური მეთოდი წარმატებით გააქტიურდა'
        },
        financialByTypeSet: {
            success: 'ფინანსური მეთოდი წარმატებით გააქტიურდა'
        },
        deleteFinancial: {
            success: 'გამოთხოვის მეთოდი წარმატებით წაიშალა'
        },
        financialAdd: {
            bank: {
                your_name: {
                    required: 'სახელის შეყვანა აუცილებელია'
                },
                bank_name: {
                    required: 'ბანკის სახელის შეყვანა აუცილებელია'
                },
                account_number: {
                    required: 'IBAN-ი აუცილებელია'
                },
                swift: {
                    required: 'Swift კოდი აუცილებელია'
                }
            },
            success: 'გამოთხოვის მეთოდი წარმატებით დაემატა',
            update: 'გამოთხოვის მეთოდი წარმატებით განახლდა'
        },
        driversLicense: {
            license_number: {
                required: 'მართვის მოწმობის ნომრის შეყვანა აუცილებელია'
            },
            front_side: {
                required: 'გთხოვთ ატვირთოთ მართვის მოწმობის წინა მხარე',
                wrongFormat: 'სურათის ფორმატი არასწორია',
                lessThan: 'სურათის ზომა არ უნდა აღემატებოდეს 4 მეგაბაიტს'
            },
            back_side: {
                required: 'გთხოვთ ატვირთოთ მართვის მოწმობის უკანა მხარე',
                wrongFormat: 'სურათის ფორმატი არასწორია',
                lessThan: 'სურათის ზომა არ უნდა აღემატებოდეს 4 მეგაბაიტს'
            },
            active: 'მართვის მოწმობის დეტალები დადასტურდა',
            pending: 'მართვის მოწმობის დეტალები განხილვის პროცესშია',
            rejected: 'მართვის მოწმობის დეტალები უარყოფილია',
            success: 'მართვის მოწმობის დეტალები გამოიგზავნა შესამოწმებლად'
        },
        vehicleAdd: {
            type: {
                required: 'გთხოვთ მონიშნოთ ტრანსპორტის ტიპი',
                invalid: 'ტრანსპორტის ტიპი არასწორია'
            },
            manufacturer: {
                required: 'გთხოვთ მონიშნოთ მწარმოებელი'
            },
            model: {
                required: 'გთხოვთ შეიყვანოთ მოდელი'
            },
            registration_country: {
                required: 'გთხოვთ მონიშნოთ რეგისტრაციის ქვეყანა'
            },
            license_number: {
                required: 'გთხოვთ შეიყვანოთ სახელმწიფო ნომერი'
            },
            year: {
                required: 'გთხოვთ მონიშნოთ გამოშვების წელი'
            },
            date_of_registration: {
                required: 'გთხოვთ შეიყვანოთ რეგისტრაციის თარიღი'
            },
            front_side: {
                required: 'გთხოვთ ატვირთოთ რეგისტრაციის მოწმობის წინა მხარე',
                wrongFormat: 'სურათის ფორმატი არასწორია',
                lessThan: 'სურათის ზომა არ უნდა აღემატებოდეს 4 მეგაბაიტს'
            },
            back_side: {
                required: 'გთხოვთ ატვირთოთ რეგისტრაციის მოწმობის უკანა მხარე',
                wrongFormat: 'სურათის ფორმატი არასწორია',
                lessThan: 'სურათის ზომა არ უნდა აღემატებოდეს 4 მეგაბაიტს'
            },
            success: 'მონაცემები წარმატებით გაიგზავნა შესამოწმებლად'
        },
        vehicleEdit: {
            success: 'მონაცემები წარმატებით გაიგზავნა შესამოწმებლად',
            active: 'ტრანსპორტის დეტალები დადასტურდა',
            pending: 'ტრანსპორტის დეტალები განხილვის პროცესშია',
            rejected: 'ტრანსპორტის დეტალები უარყოფილია'
        },
        routeAdd: {
            success: 'მარშრუტი წარმატებით დარეგისტრირდა'
        },
        routeEdit: {
            success: 'მარშრუტი წარმატებით განახლდა'
        },
        routeDelete: {
            success: 'მარშრუტი წარმატებით გაუქმდა/წაიშალა'
        },
        payoutAdd: {
            amount: {
                required: 'გთხოვთ შეიყვანოთ თანხა',
                number: 'გთხოვთ შეიყვანოთ მხოლოდ ციფრები',
                less: 'ბალანსზე არ არის საკმარისი თანხა'
            },
            success: 'თანხის გამოთხოვის მოთხოვნა წარმატებით გამოიგზავნა'
        },
        preserveRoute: {
            success: 'მონაცემები წარმატებით განახლდა.'
        }
    },
    en: {
        profileUpdate: {
            email: {
                required: 'Email is required',
                valid: 'Email is invalid'
            },
            avatar: {
                imageSizeSmall: 'Image dimensions should be more than 150x150 pixels',
                success: 'Avatar has been updated'
            },
            gender: {
                required: 'Gender is required'
            },
            phone_number: {
                required: 'Phone number is required',
                valid: 'Phone number format is invalid'
            },
            name: {
                required: 'Name is required'
            },
            id_number: {
                required: 'ID number is required',
                valid: 'ID number is invalid'
            },
            city: {
                required: 'Please fill out the city field'
            },
            success: 'Profile successfully updated'
        },
        partnerRegister: {
            affiliateCode: {
                required: 'Affiliate code is required'
            }
        },
        QRScanner: {
            ticket_number: {
                required: 'Please enter ticket code'
            },
            success: 'Ticket has been successfully parsed',
            cameraErrors: {
                NotAllowedError: 'You need to grant camera access permission',
                NotFoundError: 'No camera on this device',
                NotSupportedError: 'Secure context required (HTTPS)',
                NotReadableError: 'Is the camera already in use?',
                OverconstrainedError: 'Installed cameras are not suitable',
                StreamApiNotSupportedError: 'Stream API is not supported in this browser, please try another browser'
            }
        },
        signupAsPartner: {
            success: 'Registration has been successfully completed'
        },
        login: {
            phone_number: {
                required: 'Phone number is required'
            },
            password: {
                required: 'Password is required'
            },
            invalid_grant: 'Phone number or password is incorrect'
        },
        signup: {
            phone_number: {
                required: 'Phone number is required'
            },
            password: {
                required: 'Password is required'
            },
            success: 'Account has been successfully created, you can log in now.'
        },
        changePassword: {
            old_password: {
                required: 'Current password is required'
            },
            password: {
                required: 'Password is required',
                match: 'Passwords don\'t match'
            },
            success: 'Password has been successfully updated'
        },
        forgot: {
            success: 'Password has been successfully sent'
        },
        financialPrimarySet: {
            success: 'Financial method has been successfully updated'
        },
        financialByTypeSet: {
            success: 'Financial method has been successfully updated'
        },
        deleteFinancial: {
            success: 'Method has been deleted'
        },
        financialAdd: {
            bank: {
                your_name: {
                    required: 'Beneficiary is required'
                },
                bank_name: {
                    required: 'Bank name is required'
                },
                account_number: {
                    required: 'IBAN is required'
                },
                swift: {
                    required: 'Swift is required'
                }
            },
            success: 'Financial method has been added',
            update: 'Financial method has been updated'
        },
        driversLicense: {
            license_number: {
                required: 'Driver\'s license number is required'
            },
            front_side: {
                required: 'Please upload the front side',
                wrongFormat: 'Image format is incorrect.',
                lessThan: 'Image size should be less than 4 MB'
            },
            back_side: {
                required: 'Please upload the back side',
                wrongFormat: 'Image format is incorrect.',
                lessThan: 'Image size should be less than 4 MB'
            },
            active: 'Your driver\'s license has been approved',
            pending: 'Your driver\'s license is pending for approval',
            rejected: 'Your driver\'s license has been rejected',
            success: 'Your driver\'s license details have been sent for approval'
        },
        vehicleAdd: {
            type: {
                required: 'Please select vehicle type',
                invalid: 'Vehicle type is invalid'
            },
            manufacturer: {
                required: 'Please select manufacturer'
            },
            model: {
                required: 'Please enter vehicle model'
            },
            registration_country: {
                required: 'Please select registration country'
            },
            license_number: {
                required: 'Vehicle plate number is required'
            },
            year: {
                required: 'Year of manufacture is required'
            },
            date_of_registration: {
                required: 'Date of registration is required'
            },
            front_side: {
                required: 'Please upload the front side',
                wrongFormat: 'Image format is incorrect.',
                lessThan: 'Image size should be less than 4 MB'
            },
            back_side: {
                required: 'Please upload the back side',
                wrongFormat: 'Image format is incorrect.',
                lessThan: 'Image size should be less than 4 MB'
            }
        },
        vehicleEdit: {
            success: 'Vehicle details have been sent to review',
            active: 'Vehicle details have been approved',
            pending: 'Vehicle details are pending for approval',
            rejected: 'Vehicle details have been rejected'
        },
        routeAdd: {
            success: 'Route has been successfully created'
        },
        routeEdit: {
            success: 'Route has been successfully updated'
        },
        routeDelete: {
            success: 'Route has been successfully canceled/deleted'
        },
        payoutAdd: {
            amount: {
                required: 'Please enter the amount',
                number: 'Please enter the valid amount',
                less: 'Current value is not enough for withdrawal'
            },
            success: 'Withdrawal request has been successfully sent'
        },
        preserveRoute: {
            success: 'Details have been successfully updated.'
        }
    }
}
