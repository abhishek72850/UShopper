@route("/stream")

def stream():
    def eventStream():
        
    	yield "data: hello from py"
    
    return Response(eventStream(), mimetype="text/event-stream")