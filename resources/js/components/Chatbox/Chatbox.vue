<template>
    <div class="d-lg-flex">

        <div class="chat-leftsidebar mr-lg-4">
            <div class="">
                <div class="py-4 border-bottom">
                    <div class="media">
                        <div class="align-self-center mr-3" v-if="currentProfile.avatar != 'avatar.jpg'">
                            <img :src="currentUrl+'/images/avatars/'+currentProfile.avatar" class="avatar-xs rounded-circle" alt="">
                        </div>
                        <div class="lign-self-center mr-3 avatar-xs" v-else>
                            <span class="avatar-title rounded-circle">{{currentProfile.firstname.charAt(0)}}</span>
                        </div>
                        <div class="media-body">
                            <h5 class="font-size-15 mt-0 mb-1">{{ currentProfile.firstname }} {{ currentProfile.lastname }}</h5>
                            <p class="text-muted mb-0"><i class="icofont icofont-check-circled text-success align-middle mr-1"></i>{{ currentUser.type }}</p>
                        </div>

                        <div>
                            <div class="dropdown chat-noti-dropdown active">
                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-bell bx-tada"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <contact-list  @selected="startConversationWith"></contact-list>

            </div>
        </div>

        <div class="w-100 user-chat">
            <div class="card">
                        
                <chat-conversation :selecteduser="selectedContact" :selectedtype="type" :selectedconvo="conversation" :currentuser="currentProfile" ref="runcontactlist"></chat-conversation>

            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: {
        currentUser: {type: Object,required: true },
        currentProfile: {type: Object,required: true }
    },

    data() {
        return {
            currentUrl: window.location.origin,
            errors: [],
            conversation: {},
            selectedContact: {},
            type: ''
        }
    },

    methods :{
        startConversationWith(contact,type) {
            
            this.selectedContact = contact;
            this.type = type;
            
            if(type == 'Single'){
                axios.post(this.currentUrl + '/request/chatbox/conversation', {
                    id: contact.id
                })
                .then(response => {
                    this.conversation = response.data.data;
                    this.$refs.runcontactlist.listenForNewMessage(this.conversation.channel_name);
                    this.$refs.runcontactlist.scrollToBottom();
                })
                .catch(err => console.log(err));
            }else{
                axios.post(this.currentUrl + '/request/chatbox/group/conversation', {
                    id: contact.id
                })
                .then(response => {
                    this.conversation = response.data.data;
                    this.$refs.runcontactlist.listenForNewMessage(this.conversation.channel_name);
                    this.$refs.runcontactlist.scrollToBottom();
                })
                .catch(err => console.log(err));
            }

           
        },
    }
}
</script>