<template>
<div>
    <div class="p-4 border-bottom ">
        <div class="row">
            <div class="col-md-4 col-9">
                <h5 class="font-size-15 mb-1">{{ selecteduser.name }}</h5>
                <p class="text-muted mb-0"><i class="mdi mdi-circle text-success align-middle mr-1"></i> Active now</p>
            </div>
            <div class="col-md-8 col-3">
                <ul class="list-inline user-chat-nav text-right mb-0">
                    <li class="list-inline-item d-none d-sm-inline-block">
                        <div class="dropdown">
                            <button class="btn nav-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-search-alt-2"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-md">
                                <form class="p-3">
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                    <li class="list-inline-item  d-none d-sm-inline-block">
                        <div class="dropdown">
                            <button class="btn nav-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-cog"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">View Profile</a>
                                <a class="dropdown-item" href="#">Clear chat</a>
                                <a class="dropdown-item" href="#">Muted</a>
                                <a class="dropdown-item" href="#">Delete</a>
                            </div>
                        </div>
                    </li>

                    <li class="list-inline-item">
                        <div class="dropdown">
                            <button class="btn nav-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else</a>
                            </div>
                        </div>
                    </li>
                    
                </ul>
            </div>
        </div>
    </div>

    <div>
        <div class="chat-conversation p-3">
            <simplebar style="max-height: 470px; min-height: 450px;" id="containerElement" ref="feed">
                <ul class="list-unstyled mb-0">
                    <li> 
                        <div class="chat-day-title">
                            <span class="title">Today</span>
                        </div>
                    </li>
                    
                    <li v-for="message in selectedconvo.messages" v-bind:key="message.id" v-bind:class="{'right' : (message.sender.id == currentuser.user_id)}" >
                        <div class="conversation-list">
                            <div class="dropdown">

                                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                    </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Copy</a>
                                    <a class="dropdown-item" href="#">Save</a>
                                    <a class="dropdown-item" href="#">Forward</a>
                                    <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                            <div class="ctext-wrap">
                                <div class="conversation-name">{{message.sender.name}}</div>
                                <p>
                                {{ message.text }}
                                </p>
                                
                                <p class="chat-time mb-0"><small><i class="bx bx-time-five align-middle mr-1"></i> <timeago :datetime="message.created_at" :auto-update="10"></timeago></small></p>
                            </div>
                        </div>
                    </li>
                </ul>
            </simplebar>
            
             <p style=" margin-bottom: -20px;" class="text-center" v-if="typingUser.lastname"><small> {{ typingUser.firstname + ' ' + typingUser.lastname }} is typing</small></p>
        </div>
        <div class="p-3 chat-input-section">
            <form @submit.enter.prevent="send">
                <div class="row">
                    <div class="col">
                        <div class="position-relative">
                            <input @keyup="typing" type="text" v-model="text" class="form-control chat-input" placeholder="Enter Message...">
                            <div class="chat-input-links">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item"><a href="#" data-toggle="tooltip" data-placement="top" title="Emoji"><i class="mdi mdi-emoticon-happy-outline"></i></a></li>
                                    <li class="list-inline-item"><a href="#" data-toggle="tooltip" data-placement="top" title="Images"><i class="mdi mdi-file-image-outline"></i></a></li>
                                    <li class="list-inline-item"><a href="#" data-toggle="tooltip" data-placement="top" title="Add Files"><i class="mdi mdi-file-document-outline"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-rounded chat-send w-md waves-effect waves-light">
                            <span class="d-none d-sm-inline-block mr-2">Send</span> <i class="mdi mdi-send"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
import simplebar from 'simplebar-vue';
export default {
    props: {
        selecteduser: {type: Object,required: true },
        selectedtype: {type: String,required: true },
        selectedconvo: {type: Object,required: true },
        currentuser: {type: Object, required: true},
    },

    data() {
        return {
            currentUrl: window.location.origin,
            errors: [],
            text: '',
            typingUser : {},
            typingClock: null,
        }
    },

    watch: {

    },

    methods : {
       send() {
            if(this.text != ''){
                if(this.selectedtype == 'Single'){
                    axios.post('/request/chatbox/chat/message/send', {
                        conversationId: this.selectedconvo.conversationId,
                        text: this.text,
                    }).then((response) => {
                        this.text = '';
                    });
                }else{
                    axios.post('/request/chatbox/group/chat/message/send', {
                        conversationId: this.selectedconvo.conversationId,
                        text: this.text,
                    }).then((response) => {
                        this.text = '';
                    });

                }
            }
        },

        listenForNewMessage(channel){
            Echo.join(channel)
                .here((users) => {
                    // console.log(users)
                })
                .listen('NewConversationMessage', (data) => {
                    this.selectedconvo.messages.push(data);
                    this.scrollToBottom();
                })
                .listenForWhisper('typing', (e) => {
                    this.typingUser = e.user;
                    this.scrollToBottom();
                    if(this.typingClock) clearTimeout();

                    this.typingClock = setTimeout(() => {
                        this.typingUser = {};
                    },3000)
                });
        },

        typing(){
            Echo.join(this.selectedconvo.channel_name)
            .whisper('typing', {
                user: this.currentuser
            });
        },

        scrollToBottom() {
            
            // console.log( this.$refs.feed.scrollElement.scrollHeight);
            // this.$refs.feed.scrollElement.addEventListener(
            //     "scroll",
            //     this.handleScroll()
            // );
            
            setTimeout(() => {
                this.$refs.feed.scrollElement.scrollTop = this.$refs.feed.scrollElement.scrollHeight - this.$refs.feed.scrollElement.clientHeight;
            }, 50);
        },

    }, components: { simplebar }
}
</script>