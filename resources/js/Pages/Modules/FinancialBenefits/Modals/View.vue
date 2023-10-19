<template>
    <b-modal v-model="showModal" title="View Financial Benefit" header-class="p-3 bg-light" size="xl" class="v-modal-custom" modal-class="zoomIn" centered hide-footer>    
        <b-form class="customform mb-2">
            <b-row class="mb-4 mt-2">
                <div class="responsive">
                    <table class="table table-centered table-bordered table-nowrap">
                        <thead class="thead-light align-middle text-center">
                            <tr class="fw-bold fs-12">
                                <td>Name</td>
                                <td>Total</td>
                            </tr>
                        </thead>
                        <tbody class="align-middle text-center fw-semibold text-primary fs-12">
                            <tr>
                                <td width="50%">{{selected.name}} </td>
                                <td width="50%">₱ {{ formatMoney(selected.total)}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered table-nowrap align-middle mb-0">
                        <thead class="table-dark">
                            <tr class="fs-11">
                                <th class="text-center" width="16%">Semester</th>
                                <th v-for="(l,index) in headers" v-bind:key="index" :width="73/headers.length+'%'" class="text-center">{{l}}</th>
                                <th class="text-center" width="11%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(e,index) in selected.enrollments" v-bind:key="index">
                                <tr class="fs-11" v-if="(counts[index] != 0) ? true : false">
                                    <td class="text-center">{{e.academic_year}} - {{e.semester}}</td>
                                    <td v-for="(l,index) in headers" v-bind:key="index" class="text-center">
                                        ₱{{calculate(l,e)}}
                                    </td>
                                    <td class="text-center"> 
                                        ₱{{formatMoney(e.total)}}
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <thead>
                            <tr class="table-light fs-11 fw-semibold">
                                <td class="text-center">Total</td>
                                <td v-for="(l,index) in headers" v-bind:key="index" class="text-center">
                                    ₱{{formatMoney(check(l))}}
                                </td>
                                <td class="text-center">₱ {{formatMoney(selected.total)}}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </b-row>
        </b-form>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            selected: '',
            height: window.innerHeight - 557,
            breakdown: '',
            show: false,
            headers: [],
            totals: [],
            counts: [],
        }
    },
    methods : {
        set(data){
            this.headers = [];
            this.totals = [];
            this.show = false;
            this.selected = data;
            this.showModal = true;
            this.counts = [];

            this.selected.enrollments.map((list) => {
                list.benefits.map((list) => {
                    if (!this.headers.includes(list.benefit.short)) {
                        this.headers.push(list.benefit.short);
                    }
                });
            });
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        calculate(data,benefits){
            if(data != 'Total'){
                var total = 0;
                benefits.benefits.map((list) => {
                    if(list.benefit.short == data) {
                        total = parseInt(total) + parseInt(list.amount);
                    }
                });
                this.totals.push(total);
                return this.formatMoney(total);
            }else{
                let t = this.formatMoney(this.totals.reduce((a, b) => a + b, 0));
                this.totals = [];
                return t;
            };
            return 0;
        },
        check(l){
            var total = 0;
            this.selected.enrollments.map((list) => {
                list.benefits.map((list) => {
                    if(list.benefit.short == l) {
                    total = parseInt(total) + parseInt(list.amount);
                }
                });
            });
            return total;
        }
    }
}
</script>