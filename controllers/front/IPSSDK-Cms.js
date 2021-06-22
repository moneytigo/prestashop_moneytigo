var Ips = Ips || {};
Ips.createCreditCardIframe =
  Ips.createCreditCardIframe
  || function (btn_container_id, iframe_container_id, paramsPayment) {
    if (!paramsPayment) {
      console.log("SDK JS : FATAL ERROR: No transaction data received");
    }
    if (typeof iframe_container_id !== "string") {
      return {
        error: "invalid_parameter",
        error_description: "Expected iframe_container_id value in [ string ]."
      };
    }
    if (typeof btn_container_id !== "string") {
      return {
        error: "invalid_parameter",
        error_description: "Expected btn_container_id value in [ string ]."
      };
    }
    var element = document.getElementById(iframe_container_id);
    var elementbtn = document.getElementById(btn_container_id);

    if (!element) {
      return {
        error: "invalid_parameter",
        error_description: "ID " + iframe_container_id + " not found on window.document."
      };
    }
    if (!elementbtn) {
      return {
        error: "invalid_parameter",
        error_description: "ID " + btn_container_id + " not found on window.document."
      };
    }
    var credit_card_iframe_instance = new Ips.createCreditCardIframe._internal.creditCardIframe();
    credit_card_iframe_instance.init(btn_container_id, iframe_container_id, paramsPayment);
    return credit_card_iframe_instance;
  };

Ips.createCreditCardIframe.allowOptions = function (property) {
  var allowPropertyInOptions = new Set(["custom_style"]);
  if (!allowPropertyInOptions.has(property)) {
    console.warn(property + " is not a supported property.");
    return false;
  }
  return true;
};

Ips.createCreditCardIframe._internal =
  Ips.createCreditCardIframe._internal
  || new(function () {
    var ActiveDebugMode = false;

    function DebugMode(tips) {
      if (ActiveDebugMode === true) {
        console.log("SDK : " + tips);
      }
    }

    this.creditCardIframe = function () {
      var props = {
        is_init: false,
        iframe_id: undefined,
        callback_map: {},
        custom_style: undefined,
        callback_count: 0,
        iframe_endpoint: undefined,
        iframe_container_id: undefined
      };
      DebugMode("Frame id receipt : " + iframe_container_id);
      DebugMode("Button Validation id receipt : " + btn_container_id);
      var init = function (btn_container_id, iframe_container_id, paramsPayment) {
        if (!props.is_init) {
          props.is_init = true;
          props.iframe_container_id = iframe_container_id;
          props.iframe_id = iframe_container_id + "_iframe";
          _generateIframeUrl(paramsPayment);
          var iframe = document.createElement("iframe");
          iframe.id = props.iframe_id;
          props.iframe = iframe;
          props.iframe.style.border = "none";
          props.iframe.style.width = "100%";
          props.iframe.src = props.iframe_src;
          document.getElementById(props.iframe_container_id).appendChild(props.iframe);
          props.iframe.addEventListener("load", function () {
            window.addEventListener("message", _receiveMessageHandler, false);
          });


        }


        DebugMode("Frame initialization success");
      };

      var Execute = function (callback) {
        DebugMode("Execute function is called");
        var callback_key = props.callback_count++;
        props.callback_map[callback_key] = {
          callback: callback
        }; //charge callback function from parent

        DebugMode("Sent action to IPS API");
        var iframe_element = document.getElementById(props.iframe_id);
        var submitform = {
          'callback_count': callback_key,
          event_id: "StartSubmit",
          data: "Lauch",
          ref_id: props.ref_id,
          callback_count: callback_key
        };
        props.iframe = document.getElementById(props.iframe_id).contentWindow;
        props.iframe.postMessage(submitform, props.iframe_src); //on envoie l'info a IPS sur lapi


        if (!iframe_element) {
          if (error.hasError()) {
            props.callback_map[callback_key].callback({
              error: "invalid_parameter",
              error_description: "ID " + props.iframe_id + " not found on window.document."
            });
          }
        } else {
          loadScript("https://payment.moneytigo.com/6598874bb8d7bfdb56df4b5d6f4b56d/js/iframeResizer.min.js", function () {
            iFrameResize({
              enablePublicMethods: true,
              checkOrigin: false,
              scrolling: true,
              log: false,
              bodyMargin: '0px',
              heightCalculationMethod: 'max'
            });
          });


        }
      };

      var _receiveMessageHandler = function (event) {


        if (event.data.indexOf('iFrameSizer') <= 0) {

          var event_data = JSON.parse(event.data);


          var callback_count = event_data.callback_count;
          if (callback_count != null) {
            if (props.callback_map[callback_count] == null) {
              return;
            }
          }


          if (event_data.event_id === "ips-formsubmit" && event_data.data != null) {

            var Container3DS = window.parent.document.getElementById(iframe_container_id);
            Container3DS.innerHTML = ''; //erase all process just receipt message !

            props.callback_map[callback_count].callback(event_data.data);
            delete props.callback_map[callback_count];
            return;
          } else if (event_data.event_id === "IPSAnswer" && event_data.data === "YES") {

            var BTNSUBMT = window.parent.document.getElementById(btn_container_id);
            BTNSUBMT.style.visibility = 'hidden'; //erase all process just receipt message !

          } else if (event_data.event_id === "IPSAnswer" && event_data.data === "REPAYMENT") {

            var BTNSUBMT = window.parent.document.getElementById(btn_container_id);
            BTNSUBMT.style.visibility = 'visible'; //erase all process just receipt message !
          } else if (event_data.event_id === "PaymentRedirect" && event_data.Uri != null) //API IPS Sent Uri REDIRECT // SDK Receipt and create redirecting to parent!
          {
            var Container3DS = window.parent.document.getElementById(iframe_container_id);
            window.parent.location.href = event_data.Uri;
          }


        }

      }.bind(this);


      var _generateIframeUrl = function (paramsPayment) {

        props.ref_id = "ddddddd";
        if (paramsPayment.Token) {
          DebugMode("Receipt Token for DIRECT URI");
          var ref_id = encodeURIComponent(props.ref_id);
          var tokenTransactSACS = encodeURIComponent(paramsPayment.Token);
          props.iframe_endpoint = "https://payment.moneytigo.com/init_transactions/?SACS=";
          props.iframe_src = props.iframe_endpoint + tokenTransactSACS;
        } else {
          function encodeQuery(data) {
            DebugMode("Generate URL Payment");
            let query = ""
            for (let d in data)
              query += encodeURIComponent(d) + '='
              + encodeURIComponent(data[d]) + '&'
            return query.slice(0, -1)
          }
          var ref_id = encodeURIComponent(props.ref_id);
          var parames = encodeQuery(paramsPayment);
          props.iframe_endpoint = "https://payment.moneytigo.com/?";
          props.iframe_src = props.iframe_endpoint + parames + "&crossorigine=embededadvanced&cmsembed=true";
        }
        if (props.iframe_src.length > 2000) {
          props.iframe_src = props.iframe_endpoint + iframe_route + "?ref_id=" + ref_id;
        }
        DebugMode("Frame SRC : " + props.iframe_src);
        DebugMode("Url generated with success");
      };
      return {
        init: init,
        Execute: Execute
      };

    };

    loadScript("https://payment.moneytigo.com/6598874bb8d7bfdb56df4b5d6f4b56d/js/iframeResizer.min.js", function () {
      iFrameResize({
        enablePublicMethods: true,
        checkOrigin: false,
        scrolling: true,
        log: false,
        bodyMargin: '0px',
        heightCalculationMethod: 'documentElementScroll'
      });
    });

    return this;
  })();


function loadScript(url, callback) {
  var script = document.createElement("script")
  script.type = "text/javascript";
  if (script.readyState) { // only required for IE <9
    script.onreadystatechange = function () {
      if (script.readyState === "loaded" || script.readyState === "complete") {
        script.onreadystatechange = null;
        callback();
      }
    };
  } else { //Others
    script.onload = function () {
      callback();
    };
  }

  script.src = url;
  document.getElementsByTagName("head")[0].appendChild(script);
}
