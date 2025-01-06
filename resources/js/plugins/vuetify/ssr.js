// Vuetify
import 'vuetify/styles'
import {createVuetify} from 'vuetify'

export default createVuetify({
    theme: {
        defaultTheme: 'dark',
        themes: {
            dark: {
                colors: {
                    primary: '#15171A',
                    success: '#28A745',
                    warning: '#FFC107',
                    danger: '#DC3545',
                },
            },
        },
    },
    defaults: {
        VTextField: {
            class: 'theme__input',
            variant: 'plan'
        },
        VSelect: {
            class: 'theme__select',
            variant: 'plan'
        },
        VAutocomplete: {
            class: 'theme__autocomplete',
            variant: 'plan'
        },
        VCombobox: {
            class: 'theme__combobox',
            variant: 'plan'
        },
        VBtn: {
            rounded: 'pill'
        },
        VTabs: {
            class: 'ChoosePlan',
        },
        VFileInput: {
            class: 'theme__file',
        }
    },
    ssr: true,
})
