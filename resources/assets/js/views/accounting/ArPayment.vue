<template>
    <div>
        <notifications group="notification" />
        <b-modal
            v-model="showModalPeriod"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
            @shown="focusElement('period_id')"
        >
            <div slot="modal-title">
                Payment Date
            </div>
            <b-row>
                <b-col lg=12>
                    <b-form-group>
                            <label for="start_date">Start Date</label>
                            <date-picker 
                                ref="start_date"
                                id="start_date"
                                v-model="forms.period.fields.start_date"
                                lang="en" 
                                input-class="form-control mx-input"
                                format="MMMM DD, YYYY"
                                :clearable="false">
                            </date-picker>
                        </b-form-group>

                        <b-form-group>
                            <label for="end_date">End Date</label>
                            <date-picker 
                                ref="end_date"
                                id="end_date"
                                v-model="forms.period.fields.end_date"
                                lang="en" 
                                input-class="form-control mx-input"
                                format="MMMM DD, YYYY"
                                :clearable="false">
                            </date-picker>
                        </b-form-group>
                </b-col>
            </b-row>
            <div v-if="showConfirmation==false" slot="modal-footer">
                <b-button variant="primary" @click="showConfirmation=true">
                    <i class="fa fa-check"></i>
                    Send to Accounting
                </b-button>
                <b-button variant="secondary" @click="showModalPeriod=false">Close</b-button>            
            </div>
            <div v-else slot="modal-footer">
                Are you sure you want to continue? This transaction is irreversable.
                <b-button class="ml-2" variant="success" @click="showConfirmation=false, onInsertAr()">
                    <i class="fa fa-check"></i>
                    Yes
                </b-button>
                <b-button variant="danger" @click="showConfirmation=false">No</b-button>            
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: 'ar_payment',
    data () {
        return {
            showModalPeriod: true,
            showConfirmation: false,
            forms: {
                period: {
                    fields: {
                        start_date: new Date(),
                        end_date: new Date(),
                    }
                }
            },
            options: {
                periods: {
                    items: []
                },
            },
        }
    },
    methods:{
        async onInsertAr () {
            var start_date = this.moment(this.forms.period.fields.start_date, 'YYYY-MM-DD')
            var end_date = this.moment(this.forms.period.fields.end_date, 'YYYY-MM-DD')
            await this.$http.get('api/arpayment/getjournalinfo/' + start_date + '/' + end_date,  {
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
                this.$http.post('api/arpayment/insertjournalinfo', {info: response.data, start_date: start_date, end_date: end_date}, {
                    headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                })
                .then((response) => {
                    this.$notify({
                        type: 'success',
                        group: 'notification',
                        title: 'Success',
                        text: "Successfully sent to Accounting."
                    })
                })
                .catch(error => {
                    if (!error.response) return
                    console.log(error)
                })
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },
        getPeriodInfo: function (value, data){
            if(data.length > 0){
                var period = this.options.periods.items[data[0].element.index]
                this.forms.period.fields.period_due_date = moment(period.period_due_date).format('MMMM DD, YYYY')
                this.forms.period.fields.app_year = period.app_year
                this.forms.period.fields.month_name = period.month_name
                this.forms.period.fields.month_id = period.month_id
                this.forms.period.fields.due_date = moment(period.period_due_date).format('MMMM DD, YYYY')
            }
        },
    },
    created(){
        this.fillOptionsList('periods')
    },
}
</script>