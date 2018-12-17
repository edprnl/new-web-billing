import Vue from 'vue'
import Router from 'vue-router'

// Containers
import Full from '@/containers/Full'

// Views
import Dashboard from '@/views/Dashboard'
import Charts from '@/views/Charts'
import Widgets from '@/views/Widgets'

// Views - Components
import Buttons from '@/views/components/Buttons'
import SocialButtons from '@/views/components/SocialButtons'
import Cards from '@/views/components/Cards'
import Forms from '@/views/components/Forms'
import Modals from '@/views/components/Modals'
import Switches from '@/views/components/Switches'
import Tables from '@/views/components/Tables'

// Views - Icons
import FontAwesome from '@/views/icons/FontAwesome'
import SimpleLineIcons from '@/views/icons/SimpleLineIcons'

// Views - Pages
import Page404 from '@/views/pages/Page404'
import Page500 from '@/views/pages/Page500'
import Login from '@/views/pages/Login'
import Logout from '@/views/pages/Logout'
import Register from '@/views/pages/Register'

//Views - References
import tenants from '@/views/references/Tenants'
import departments from '@/views/references/Departments'
import charges from '@/views/references/Charges'
import categories from '@/views/references/Categories'
import locations from '@/views/references/Locations'
import contracttypes from '@/views/references/ContractTypes'
import checktypes from '@/views/references/CheckTypes'
import natureofbusiness from '@/views/references/NatureOfBusiness'
import billingperiods from '@/views/references/BillingPeriods'

//Views - Utilities
import users from '@/views/utilities/Users'

//Views - Transactions
import contracts from '@/views/transactions/Contracts'
import billing from '@/views/transactions/Billing'
import payment from '@/views/transactions/Payment'

//Views - Reports
import soa from '@/views/reports/Soa'

import store from '../store'

Vue.use(Router)

const router = new Router({
  mode: 'hash', // Demo is living in GitHub.io, so required!
  linkActiveClass: 'open active',
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: '/',
      redirect: '/dashboard',
      name: 'Home',
      component: Full,
      children: [
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard,
          meta: { requiresAuth: true }
        },
        {
          path: 'charts',
          name: 'Charts',
          component: Charts
        },
        {
          path: 'references',
          name: 'References',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'tenants',
              name: 'Tenants',
              component: tenants,
              meta: {requiresAuth: true}
            },
            {
              path: 'departments',
              name: 'Departments',
              component: departments,
              meta: {requiresAuth: true}
            },
            {
              path: 'categories',
              name: 'Categories',
              component: categories,
              meta: {requiresAuth: true}
            },
            {
              path: 'charges',
              name: 'Charges',
              component: charges,
              meta: {requiresAuth: true}
            },
            {
              path: 'locations',
              name: 'Locations',
              component: locations,
              meta: {requiresAuth: true}
            },
            {
              path: 'contracttypes',
              name: 'Contract Types',
              component: contracttypes,
              meta: {requiresAuth: true}
            },
            {
              path: 'checktypes',
              name: 'Check Types',
              component: checktypes,
              meta: {requiresAuth: true}
            },
            {
              path: 'natureofbusiness',
              name: 'Nature Of Business',
              component: natureofbusiness,
              meta: {requiresAuth: true}
            },
            {
              path: 'billingperiods',
              name: 'Billing Periods',
              component: billingperiods,
              meta: {requiresAuth: true}
            },

          ]
        },
        {
          path: 'utilities',
          name: 'Utilities',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'users',
              name: 'Users',
              component: users,
              meta: {requiresAuth: true}
            }
          ]
        },
        {
          path: 'reports',
          name: 'Reports',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'soa',
              name: 'Soa',
              component: soa,
              meta: { requiresAuth: true }
            }
          ]
        },
        {
          path: 'transactions',
          name: 'Transactions',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'contracts',
              name: 'Contracts',
              component: contracts,
              meta: {requiresAuth: true}
            },
            {
              path: 'billing',
              name: 'Billing',
              component: billing,
              meta: {requiresAuth: true}
            },
            {
              path: 'payment',
              name: 'Payment',
              component: payment,
              meta: {requiresAuth: true}
            }
          ]
        },
        {
          path: 'components',
          redirect: '/components/buttons',
          name: 'Components',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'buttons',
              name: 'Buttons',
              component: Buttons
            },
            {
              path: 'social-buttons',
              name: 'Social Buttons',
              component: SocialButtons
            },
            {
              path: 'cards',
              name: 'Cards',
              component: Cards
            },
            {
              path: 'forms',
              name: 'Forms',
              component: Forms
            },
            {
              path: 'modals',
              name: 'Modals',
              component: Modals
            },
            {
              path: 'switches',
              name: 'Switches',
              component: Switches
            },
            {
              path: 'tables',
              name: 'Tables',
              component: Tables
            }
          ]
        },
        {
          path: 'icons',
          redirect: '/icons/font-awesome',
          name: 'Icons',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'font-awesome',
              name: 'Font Awesome',
              component: FontAwesome
            },
            {
              path: 'simple-line-icons',
              name: 'Simple Line Icons',
              component: SimpleLineIcons
            }
          ]
        }
      ]
    },
    // {
    //   path: '/pages',
    //   redirect: '/pages/404',
    //   name: 'Pages',
    //   component: {
    //     render (c) { return c('router-view') }
    //   },
    //   children: [
    //     {
    //       path: '404',
    //       name: 'Page404',
    //       component: Page404
    //     },
    //     {
    //       path: '500',
    //       name: 'Page500',
    //       component: Page500
    //     },
    //     {
    //       path: 'login',
    //       name: 'Login',
    //       component: Login
    //     },
    //     {
    //       path: 'register',
    //       name: 'Register',
    //       component: Register
    //     }
    //   ]
    // },
    {
      path: '/register',
      name: 'Register',
      component: Register
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/logout',
      name: 'Logout',
      component: Logout
    },
    {
      path: '*',
      name:'404', 
      component: Page404
    },
  ]
})
export default router
router.beforeEach((to, from, next) => {
// check if the route requires authentication and user is not logged in
  if (to.matched.some(route => route.meta.requiresAuth) && !store.state.isLoggedIn) {
    // redirect to login page
    next({ name: 'Login' })
    return
  }

// if logged in redirect to dashboard
  if(to.path === '/login' && store.state.isLoggedIn) {
      next({name: from.name})
      return
  }

next()
})
