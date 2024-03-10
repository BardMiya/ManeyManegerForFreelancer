import { createRouter, createWebHistory } from 'vue-router'
import Estimates from '@/components/pages/Estimates.vue'
import Estimate from '@/components/pages/estimates/Estimate.vue'
import Home from '@/components/pages/Home.vue'
import Invoices from '@/components/pages//Invoices.vue'
import Invoice from '@/components/pages/invoices/Invoice.vue'
import Ledgers from '@/components/pages/Ledgers.vue'
import Ledger from '@/components/pages/accounting/Ledgers.vue'
import Products from '@/components/pages/Products.vue'
import Product from '@/components/pages/products/Product.vue'
import Profiles from '@/components/pages/Profiles.vue'
import Business from '@/components/pages/profiles/Business.vue'
import Partners from '@/components/pages/Partners.vue'
import Partner from '@/components/pages/partners/Partner.vue'
import SignIn from '@/components/pages/SignIn.vue'
import Works from '@/components/pages/Works.vue'
import Work from '@/components/pages/works/Work.vue'
import Accounting from '@/components/pages/AccountingCalculation.vue'

export const router = createRouter({
    history: createWebHistory(),
    routes: [
      {
        path: '/',
        name: 'Home',
        component: Home
      },
      {
        path: '/profiles',
        name: 'Profiles',
        component: Profiles,
        children: [
          { path: 'business', component: Business }  
        ],
      },
      {
        path: '/partners',
        name: 'Partners',
        component: Partners,
        children:[
            {path: ':id', component: Partner, props: true}
        ]
      },
      {
        path: '/order',
        children:[
            {
                path: '/estimates',
                name: 'Estimates',
                component: Estimates,
                children:[
                    {path: ':id', component: Estimate, props: true}
                ]
              },
              {
                path: '/invoices',
                name: 'Invoices',
                component: Invoices,
                children:[
                    {path: ':id', component: Invoice, props: true}
                ]
              },
              {
                path: '/Works',
                name: 'Works',
                component: Works,
                children:[
                    {path: ':id', component: Work, props: true}
                ]
              },
        ]
      },
      {
        path: '/ledgers',
        name: 'Ledgers',
        component: Ledgers,
        children:[
            {path: ':type', component: Ledger, props: true}
        ]
      },
      {
        path: '/accounting',
        name: 'Accounting',
        component: Accounting
      },
      {
        path: '/products',
        name: 'Products',
        component: Products,
        children:[
            {path: ':id', component: Product, props: true}
        ]
      },
      {
        path: '/sign-in',
        name: 'SignIn',
        component: SignIn,
      }
    ],
  })
  