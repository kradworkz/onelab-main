<template>
    <div>

        <div class="search-box chat-search-box py-4">
            <div class="position-relative">
                <input type="text" class="form-control" placeholder="Search...">
               <i class="bx bx-search-alt search-icon"></i>
            </div>
        </div>

        <div class="chat-leftsidebar-nav">

            <ul class="nav nav-pills nav-justified" style="background-color: white;">
                <li class="nav-item">
                    <a href="#chat" data-toggle="tab" aria-expanded="true" class="nav-link">
                        <i class="bx bx-chat font-size-20 d-sm-none"></i>
                        <span class="d-none d-sm-block">Chat</span>
                    </a>
                </li>
                <li class="nav-item" @click="type = 'Groups'">
                    <a href="#group" data-toggle="tab" aria-expanded="false" class="nav-link active">
                        <i class="bx bx-group font-size-20 d-sm-none"></i>
                        <span class="d-none d-sm-block">Group</span>
                    </a>
                </li>
                <li class="nav-item" @click="type = 'Single'">
                    <a href="#contact" data-toggle="tab" aria-expanded="false" class="nav-link">
                        <i class="bx bx-book-content font-size-20 d-sm-none"></i>
                        <span class="d-none d-sm-block">Contacts</span>
                    </a>
                </li>
            </ul>
            
            <div class="tab-content py-4">
                <div class="tab-pane" id="chat">
                    <h5 class="font-size-14 mb-3">Recent</h5>
                    <ul class="list-unstyled chat-list" data-simplebar style="max-height: 410px;">
                         <li class="active">
                            <a href="#">
                                <div class="media">
                                    <div class="align-self-center mr-3">
                                        <i class="mdi mdi-circle font-size-10"></i>
                                    </div>
                                    <div class="align-self-center mr-3">
                                        <!-- <img src="assets/images/users/avatar-2.jpg" class="rounded-circle avatar-xs" alt=""> -->
                                    </div>
                                    
                                    <div class="media-body overflow-hidden">
                                        <h5 class="text-truncate font-size-14 mb-1">Steven Franklin</h5>
                                        <p class="text-truncate mb-0">Hey! there I'm available</p>
                                    </div>
                                    <div class="font-size-11">05 min</div>
                                </div>
                            </a>
                        </li>      
                    </ul>
                </div>
                <div class="tab-pane show active" id="group">
                    <h5 class="font-size-14 mb-3">Group</h5>
                    <ul class="list-unstyled chat-list" data-simplebar style="max-height: 410px;">
                        <li>
                            <a v-for="group in groups" v-bind:key="group.id" @click="selectContact(group)">
                                <div class="media align-items-center">
                                    <div class="avatar-xs mr-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                            {{group.name.charAt(0)}}
                                        </span>
                                    </div>
                                    
                                    <div class="media-body">
                                        <h5 class="font-size-14 mb-0">{{group.name}}</h5>
                                    </div>
                                </div>
                            </a>
                        </li>
                        
                    </ul>
                </div>
                <div class="tab-pane" id="contact">
                    <h5 class="font-size-14 mb-3">Contact</h5>
                    <ul class="list-unstyled chat-list" data-simplebar style="max-height: 410px;">
                        <li>
                            <a v-for="user in contacts" v-bind:key="user.id" @click="selectContact(user)">
                                <div class="media align-items-center">
                                    <div class="avatar-xs mr-3" v-if="user.avatar == 'avatar.jpg'">
                                        <span class="avatar-title rounded-circle">{{user.name.charAt(0)}}</span>
                                    </div>
                                    <div v-else>
                                        <img class="rounded-circle avatar-xs  mr-3" :src="currentUrl+'/images/avatars/'+user.avatar" alt="">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="font-size-14 mb-0">{{user.name}}</h5>
                                        <p v-if="checkUser(user)" class="text-muted mb-0"> <small><i class="mdi mdi-circle text-success align-middle mr-1"></i>Active now</small></p>
                                        <p v-else class="text-muted mb-0"> <small><i class="mdi mdi-circle text-danger align-middle mr-1"></i> Offline</small></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            currentUrl: window.location.origin,
            errors: [],
            contacts: [],
            groups: [],
            onlineUsers: [],
            conversation: [],
            selected: null,
            keyword: '',
            type: 'Group'
        }
    },

    created(){
        this.getContacts();
        this.getGroups();
    },

    methods : {

        getGroups() {
            axios.get(this.currentUrl + '/request/chatbox/groups')
            .then(response => {
                this.groups = response.data.data;
                this.selectContact(this.groups[0]);
            })
            .catch(err => console.log(err));
        },

        selectContact(contact){
            this.$emit('selected', contact,this.type);
        },

        getContacts() {
            axios.get(this.currentUrl + '/request/chatbox/contacts')
            .then(response => {
                this.contacts = response.data.data;

                Echo.join('Online')
                    .here((users) => {
                        this.onlineUsers = users;
                    })
                    .joining((user) => {
                        this.onlineUsers.push(user);
                    })
                    .leaving((user) => {
                        this.onlineUsers.splice(this.onlineUsers.indexOf(user), 1);
                    });
            })
            .catch(err => console.log(err));
        },

        checkUser: function (user) {
            return _.find(this.onlineUsers, {
                id: user.id
            });
        },

        search() {
            axios.post(this.currentUrl + '/contact/search', {
                word: this.key,
            })
            .then(response => {
                this.contacts = response.data.data;
            })
            .catch(err => console.log(err));
        },
    }
}
</script>