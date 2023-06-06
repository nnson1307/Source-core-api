<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import serviceJs from '@/services/service.js'

export default {
    components: {
        Head,
        AuthenticatedLayout,
        serviceJs
    },
    props: {

    },
    data() {
        return {
            message: null,
            isRenderMessage: true,
            isTest: true
        }
    },
    created() {
        this.message = 'abc';
    },
    watch: {
        //Theo dõi giám sát đối tượng thay đổi và thực thi hành động xử lý
    },
    methods: {
        changeInput() {
            // this.message = this.$refs.myInput.value;
            serviceJs.sayHi(this.$refs.myInput.value)
        }
    },
    computed: {

    },
    mounted() {

        // this.isRenderMessage  = true;
        serviceJs.sayHi(this.message)

        // var sel = document.getElementById("myOption");
        // var text = sel.options[sel.selectedIndex].text;

        // console.log(text);
    }
}


</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create User</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div v-if="isRenderMessage == true">Đây là text 1: {{ message }}</div>
                    <div v-else>Đây là text 2: {{ message }}</div>

                    <div v-if="isTest == true">Div Test 1: {{ message }}</div>
                    <div v-else>Div test 2: {{ message }}</div>

                    <input text="text" class="form-control" v-model="message">

                    <!-- <input type="text" class="form-control" ref="myInput" @keyup="changeInput"> -->

                    <select class="form-control" id="myOption">
                        <option value="A">Text A</option>
                        <option value="B" selected>Text B</option>
                    </select>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

