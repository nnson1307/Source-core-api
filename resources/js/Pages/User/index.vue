<script>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateForm from './create.vue';
import EditForm from './edit.vue';
import Button from '@/ComponentShare/button.vue';
import Pagination from '@/ComponentShare/pagination.vue';
import Label from '@/ComponentShare/label.vue';
import TextInput from '@/ComponentShare/text-input.vue';
import moment from 'moment';

export default {
    components: {
        Head,
        Link,
        AuthenticatedLayout,
        CreateForm,
        EditForm,
        Button,
        Pagination,
        Label,
        TextInput
    },
    props: {

    },
    data() {
        return {
            listDataUser: [],
            search: '',
            page: 1,
            totalPage: 4,
            perPage: 10,
            totalUser: 0,
            isUpdateListUser: false,
            isEdit: false,
            infoUser: Object,
            moment: moment
        }
    },
    created() {

    },
    computed: {
        getIsNewUser() {
            return `${this.isUpdateListUser}|${this.page}|${this.search}`;
        },
    },
    watch: {
        //Theo dõi giám sát đối tượng thay đổi và thực thi hành động xử lý
        async getIsNewUser(newValue, oldValue) {
            const [newIsUpdateUser, newPage, newSearch] = newValue.split('|');
            const [oldIsUpdateUser, oldPage, oldSearch] = oldValue.split('|');

            if (newIsUpdateUser == 'true' || newPage != oldPage || newSearch != oldSearch) {
                axios({
                    method: 'GET',
                    url: '/api/users',
                    params: {
                        search: this.search,
                        page: this.page
                    }
                }).then(function (res) {
                    if (res.data.status == 200) {
                        //List User
                        this.listDataUser = res.data.data
                        this.totalPage = res.data.meta.last_page
                        this.perPage = res.data.meta.per_page
                        this.totalUser = res.data.meta.total

                        if (newSearch != oldSearch) {
                            this.page = 1;
                        } else if (newPage != oldPage) {
                            this.page = newPage;
                        }

                        //Flag update list user
                        this.isUpdateListUser = false;
                    }
                }.bind(this));
            }
        },
    },
    methods: {
        async edit(userId) {
            //Lấy thông tin user
            axios({
                method: 'GET',
                url: '/api/users/' + userId,
            }).then(function (res) {
                if (res.data.status == 200) {
                    this.infoUser = res.data.user;
                    this.isEdit = true;
                }
            }.bind(this));
        },
        async onTriggerUpdate({ isUpdateListUser, isEdit }) {
            this.isUpdateListUser = isUpdateListUser;
            this.isEdit = isEdit;
        },
        async clickPaginate(pageNum) {
            this.page = pageNum;
        },
    },
    mounted() {
        //Lấy dữ liệu trước khi khởi tạo DOM
        axios({
            method: 'GET',
            url: '/api/users',
            params: {
                search: this.search,
                page: this.page
            }
        }).then(function (res) {
            if (res.data.status == 200) {
                this.listDataUser = res.data.data
                this.totalPage = res.data.meta.last_page
                this.perPage = res.data.meta.per_page
                this.totalUser = res.data.meta.total
            }
        }.bind(this));
    }
}
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" v-if="isEdit == false">
                    <CreateForm @triggerUser="isUpdateListUser = $event"></CreateForm>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" v-else>
                    <EditForm @triggerUpdate="onTriggerUpdate" :infoUser="this.infoUser"></EditForm>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="form-group">
                        <Label name="Search"></Label>
                        <!-- <TextInput type="text" placeholder="Enter keyword" v-model.lazy="search"></TextInput> -->
                        <input class="form-control" type="text" placeholder="Enter keyword" v-model.lazy="search" />
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">User name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Create At</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="totalUser > 0" v-for="(v, k) in listDataUser">
                                <th scope="row">{{ this.page > 1 ? k + 1 + ((this.page - 1) * this.perPage) : k + 1 }}</th>
                                <td>{{ v.name }}</td>
                                <td>{{ v.email }}</td>
                                <td>{{ moment(v.created_at).format('DD/MM/YYYY HH:mm:ss') }} </td>
                                <td>
                                    <Button type="button" name="Edit" class="btn btn-success"
                                        style="background-color: #28a745 !important;" @click="edit(v.id)"></Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <Pagination v-if="totalUser > 0" :page-count="this.totalPage" :page-range="5" :margin-pages="2"
                        :click-handler="clickPaginate" :prev-text="'Prev'" :next-text="'Next'"
                        :container-class="'pagination'" :page-class="'page-item'" :first-last-button="true"
                        :current-page="this.page">
                    </Pagination>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
