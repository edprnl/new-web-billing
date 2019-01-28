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
      children:[
        {
          name: 'Tenants',
          url: '/references/tenants',
          //icon: 'fa-building'
        },
        {
          name: 'Departments',
          url: '/references/departments',
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Charges',
          url: '/references/charges',
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Categories',
          url: '/references/categories',
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Locations',
          url: '/references/locations',
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Contract Types',
          url: '/references/contracttypes',
          //icon: 'fas fa-code-branch'
        }
        ,
        {
          name: 'Check Types',
          url: '/references/checktypes',
          //icon: 'fas fa-code-branch'
        }
        ,
        {
          name: 'Nature Of Business',
          url: '/references/natureofbusiness',
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Billing Period',
          url: '/references/billingperiods',
          //icon: 'fas fa-code-branch'
        }


      ]
    },
    {
      name: 'Utilities',
      url: '/utilities',
      icon: 'icon-star',
      children:[
        {
          name: 'Users',
          url: '/utilities/users',
          //icon: 'fa-building'
        },
        {
          name: 'User Group',
          url: '/utilities/user_groups',
          //icon: 'fa-building'
        },
        {
          name: 'Company Settings',
          url: '/utilities/company_settings',
          //icon: 'fa-building'
        }
      ]
    },
    {
      name: 'Transactions',
      url: '/transactions',
      icon: 'icon-star',
      children:[
        {
          name: 'Contracts',
          url: '/transactions/contracts',
          //icon: 'fa-building'
        },
        {
          name: 'Billing',
          url: '/transactions/billing',
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Payment',
          url: '/transactions/payment',
          //icon: 'fas fa-code-branch'
        },
        {
          name: 'Adjustment',
          url: '/transactions/adjustment',
          //icon: 'fas fa-code-branch'
        }
      ]
    },
    {
      name: 'Reports',
      url: '/reports',
      icon: 'icon-star',
      children:[
        {
          name: 'Tenant Per Sqm Rate',
          url: '/reports/tenant_per_sqm_rate',
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
  ]
}
