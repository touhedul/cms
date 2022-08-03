export default {
    methods: {
        savePostCall(data, callBackHandler) {
            axios({
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                method: 'post',
                url: '/api/blog/post/store',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler(Helpers.unknownError(error));
            });
        },
        updatePostCall(id, data, callBackHandler) {
            axios({
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                method: 'post',
                url: '/api/blog/post/update/' + id,
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler(Helpers.unknownError(error));
            });
        },
        deletePostCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/blog/post/remove/' + id,
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', id);
                } else {
                    return callBackHandler('001', response.data.error);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        addCommentCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/blog/comment/store',
                data: data
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.error);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        removeCommentCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/blog/comment/remove/' + id,
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.error);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        }
    }
}