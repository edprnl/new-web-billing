<template>
   <div class="animated fadeIn">
        <div class="container-fluid" >
            <div class="row">
                <div class="col-lg-12">
                    <b-card header="Tenants List"> 
                        <div class="row">
                            <div class="col-sm-4">
                                <b-button variant="primary">
                                        <i class="fa fa-plus-circle"></i> Create New Tenant
                                </b-button>
                            </div>

                            <div class="col-sm-4">
                                <span></span>
                            </div>

                            <div class="col-sm-4">
                                <b-form-input type="text" id="name" placeholder="Search"></b-form-input>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12 mt-2">
                                <b-table :filter="filters.tenants.criteria"
                                         :fields="tables.tenants.fields"
                                         :items="tables.tenants.items"
                                         :current-page="paginations.tenants.currentPage"
                                         :per-page="paginations.tenants.perPage"
                                         fixed hover >

                                    <template slot="action" slot-scope="data">
                                        <b-btn variant="primary" :to="{path: 'categories/' + data.item.id }">
                                            <i class="fa fa-edit"></i>
                                        </b-btn>

                                        <b-btn variant="danger" @click="onItemDelete(data)">
                                            <i class="fa fa-trash"></i>
                                        </b-btn>
                                    </template>


                            </b-table>

                            </div>

                            
                        </div>
                        
                    </b-card>
                </div>
            </div>
        </div>
   </div>
</template>

<script>
export default {
    name: 'tenants',
    data () {
      return {
        tables: {
          tenants: {
            fields: [
              {
                key: 'tenant_name',
                label: 'Tenant NAme'
              },
              {
                key: 'tenant_desc',
                label: 'Description'
              },
              {
                key: 'action',
                label: ' '
              }
            ],
            items: [
              {
                tenant_name : 'aaa',
                tenant_desc : 'bbb'
              }
            ]
          }
        },
        filters: {
          tenants: {
            criteria: null
          }
        },
        paginations: {
          tenants: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        }
      }
    },
    created () {
      //this.fillTableList('classifications')
    }
  }
</script>

