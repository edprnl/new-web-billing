export default {
  items: [
    {
      name: 'Dashboard',
      url: '/dashboard',
      icon: 'icon-speedometer'
    },
    {
      name: 'References',
      url: '/references',
      icon: 'icon-puzzle',
      rights: [1,2,3,4,5,6,7,8,9],
      children:[
        {
          name: 'Tenants',
          url: '/references/tenants',
          rights: '1-1'
        },
        {
          name: 'Departments',
          url: '/references/departments',
          rights: '2-5'
        },
        {
          name: 'Charges',
          url: '/references/charges',
          rights: '3-9'
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Categories',
          url: '/references/categories',
          rights: '4-13'
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Locations',
          url: '/references/locations',
          rights: '5-17'
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Contract Types',
          url: '/references/contracttypes',
          rights: '6-21'
          //icon: 'fas fa-code-branch'
        }
        ,
        {
          name: 'Check Types',
          url: '/references/checktypes',
          rights: '7-25'
          //icon: 'fas fa-code-branch'
        }
        ,
        {
          name: 'Nature Of Business',
          url: '/references/natureofbusiness',
          rights: '8-29'
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Billing Period',
          url: '/references/billingperiods',
          rights: '9-33'
          //icon: 'fas fa-code-branch'
        }


      ]
    },
    {
      name: 'Utilities',
      url: '/utilities',
      icon: 'icon-star',
      rights: [10,11,12],
      children:[
        {
          name: 'Users',
          url: '/utilities/users',
          rights: '10-37'
          //icon: 'fa-building'
        },
        // {
        //   name: 'User Group',
        //   url: '/utilities/user_groups',
        //   rights: '11-41'
        // },
        {
          name: 'Company Settings',
          url: '/utilities/company_settings',
          rights: '12-45'
          //icon: 'fa-building'
        }
      ]
    },
    {
      name: 'Transactions',
      url: '/transactions',
      icon: 'icon-star',
      rights: [13,14,15,16],
      children:[
        {
          name: 'Contracts',
          url: '/transactions/contracts',
          rights: '13-46'
          //icon: 'fa-building'
        },
        {
          name: 'Billing',
          url: '/transactions/billing',
          rights: '14-50'
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Payment',
          url: '/transactions/payment',
          rights: '15-55'
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Adjustment',
          url: '/transactions/adjustment',
          rights: '16-59'
          //icon: 'fas fa-code-branch'
        }
      ]
    },
    {
      name: 'Reports',
      url: '/reports',
      icon: 'icon-star',
      rights: [17],
      children:[
        {
          name: 'Tenant Per Sqm Rate',
          url: '/reports/tenant_per_sqm_rate',
          //icon: 'fa-building'
        },
        {
          name: 'Contracts Master List',
          url: '/reports/contracts_master_list',
          //icon: 'fa-building'
        },
        {
          name: 'Rental Rates and Charges',
          url: '/reports/rental_and_charges',
          //icon: 'fa-building'
        },
      ]
    },
    // {
    //   name: 'Components',
    //   url: '/components/buttons',
    //   icon: 'icon-puzzle',
    //   children: [
    //     {
    //       name: 'Buttons',
    //       url: '/components/buttons',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Social Buttons',
    //       url: '/components/social-buttons',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Cards',
    //       url: '/components/cards',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Forms',
    //       url: '/components/forms',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Modals',
    //       url: '/components/modals',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Switches',
    //       url: '/components/switches',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Tables',
    //       url: '/components/tables',
    //       icon: 'icon-puzzle'
    //     }
    //   ]
    // },
    // // {
    //   name: 'Icons',
    //   url: '/icons',
    //   icon: 'icon-star',
    //   children: [
    //     {
    //       name: 'Font Awesome',
    //       url: '/icons/font-awesome',
    //       icon: 'icon-star'
    //     },
    //     {
    //       name: 'Simple Line Icons',
    //       url: '/icons/simple-line-icons',
    //       icon: 'icon-star'
    //     }
    //   ]
    // },
    // {
    //   name: 'Widgets',
    //   url: '/widgets',
    //   icon: 'icon-calculator',
    //   badge: {
    //     variant: 'danger',
    //     text: 'NEW'
    //   }
    // },
    // {
    //   name: 'Charts',
    //   url: '/charts',
    //   icon: 'icon-pie-chart'
    // },
    // {
    //   divider: true
    // },
    // {
    //   title: true,
    //   name: 'Extras'
    // },
    // {
    //   name: 'Pages',
    //   url: '/pages',
    //   icon: 'icon-star',
    //   children: [
    //     {
    //       name: 'Login',
    //       url: '/pages/login',
    //       icon: 'icon-star'
    //     },
    //     {
    //       name: 'Register',
    //       url: '/pages/register',
    //       icon: 'icon-star'
    //     },
    //     {
    //       name: 'Error 404',
    //       url: '/pages/404',
    //       icon: 'icon-star'
    //     },
    //     {
    //       name: 'Error 500',
    //       url: '/pages/500',
    //       icon: 'icon-star'
    //     }
    //   ]
    // }
  ],
}
