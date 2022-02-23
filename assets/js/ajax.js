function ajax(url, data = {}, func = (data) => { }, errFunc = (err) => { }) {
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        dataType: 'json',
        success: (res) => {
            console.log(res);
            if (res.ok === true)
                func(res.data);
            else {
                errFunc(res.err);
            }
        },
        error: (qXHR, textStatus, error) => {
            console.error(err);
            errFunc();
        }
    });
}

