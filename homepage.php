<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llega.top &dash; dytukMedia</title>
    <link rel="stylesheet" href="./assets/style.css">
</head>

<body>
    <section>
        <div class="container">
            <div class="questionCont" id="mainContiner">
                <h1>Llega</h1>
            </div>
        </div>
    </section>

    <div class="video">
        <video src="https://cdn.jsdelivr.net/gh/dytukMedia/llega.top@main/videoBackground.mp4" muted playsinline autoplay loop></video>
    </div>

    <script defer>
        // fix for DarkReader extension, messing with the website.
        document.onreadystatechange = function() {
            if (document.readyState == "complete") {
                const lock = document.createElement('meta');
                lock.name = 'darkreader-lock';
                document.head.appendChild(lock);
                console.log('Page fully loaded x')
            }
        }

        const FAQ = [{
                question: "Llega.top &dash; to arrive at your destination.",
                answer: "You may shorten a URL by <a onclick=\"shortenURL()\">clicking here</a>."
            },
            {
                question: "Do you offer an API?",
                answer: "Yes! You can shorten URLs by simply using <a><b><?php echo "https://{$_SERVER['HTTP_HOST']}/?url=example.com"; ?></b></a>"
            },
            {
                question: "Can I donate to you?",
                answer: "You can send us money to keep this site online by <a href=\"https://donate.stripe.com/eVa7uX6uIgTHbGU6os\">clicking here</a>"
            },
            {
                question: "Add to your browser",
                answer: "We have thrown together a <a title=\"Llega.top URL Shortener\" href=\"javascript:(function(){let url=prompt('Enter a URL');if(!url)return;const validProtocols=['http://','https://'];if(validProtocols.every(protocol=>!url.startsWith(protocol)))url='http://'+url;const validDomains=['google.com'];if(!validDomains.some(domain=>url.includes(domain)))return alert('Invalid domain.');const apiUrl='https://llega.top/?url='+encodeURIComponent(url);fetch(apiUrl).then(response=>response.ok?response.text():Promise.reject('API request failed')).then(data=>{const tempTextArea=document.createElement('textarea');tempTextArea.value=data;document.body.appendChild(tempTextArea);tempTextArea.select();document.execCommand('copy');document.body.removeChild(tempTextArea);alert('Short URL copied to clipboard.');}).catch(()=>alert('Something went wrong. Please try again.'));})();\">dragable bookmarklet</a>"
                // answer: "We have thrown together a <a title=\"Llega.top URL Shortener\" href=\"javascript:(function(){let url=prompt('Enter a URL');if(!url)return;const validProtocols=['http://','https://'];if(validProtocols.every(protocol=>!url.startsWith(protocol)))url='http://'+url;const validDomains=['google.com'];if(!validDomains.some(domain=>url.includes(domain)))return alert('Invalid domain.');const apiUrl='https://llega.top/?url='+encodeURIComponent(url);fetch(apiUrl).then(response=>response.ok?response.text():Promise.reject('API request failed')).then(data=>{const tempTextArea=document.createElement('textarea');tempTextArea.value=data;document.body.appendChild(tempTextArea);tempTextArea.select();document.execCommand('copy');document.body.removeChild(tempTextArea);alert('Short URL copied to clipboard.');}).catch(()=>alert('Something went wrong. Please try again.'));})();\">dragable bookmarklet</a> &amp; extensions for Chrome/Firefox for quicker shortening."
            },
            {
                question: "Privacy",
                answer: "We do not collect any information from users other than the URL they're shortening and when the short link was last used."
            },
            {
                question: "Copyright",
                answer: "Copyright <span style=\"font-family: Arial, sans-serif;\">&copy;</span> 2018-<?php echo date('Y'); ?> Pythona Studios. All rights reserved.<br>dytukMedia is an independent subsidiary of Pythona Studios."
            }
        ];

        const mainContinerEl = document.getElementById("mainContiner");

        function FAQuestions() {
            return FAQ.forEach((item) => {
                return createHTMLElements(item.question, item.answer);
            });
        }

        function createHTMLElements(question, answer) {
            const card = document.createElement("div");
            card.classList.add("card");
            const questionCont = document.createElement("div");
            questionCont.classList.add("question");
            card.appendChild(questionCont);
            if (question === 'Llega.top &dash; to arrive at your destination.') card.classList.add('active');
            const questionPara = document.createElement("div");
            questionPara.classList.add("questionpara");
            const questionEl = document.createElement("p");
            questionCont.appendChild(questionPara);
            questionPara.appendChild(questionEl);
            questionEl.innerHTML = question;
            const arrowCont = document.createElement("div");
            const arrowImg = document.createElement("img");
            arrowImg.src = "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAiIGhlaWdodD0iNyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMSAuNzk5bDQgNCA0LTQiIHN0cm9rZT0iI0Y0N0I1NiIgc3Ryb2tlLXdpZHRoPSIyIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiLz48L3N2Zz4=";
            arrowCont.classList.add("arrowCont");
            arrowCont.appendChild(arrowImg);
            questionCont.appendChild(arrowCont);

            // Answer Element
            const answerCont = document.createElement("div");
            answerCont.classList.add("answer");
            const answerPara = document.createElement("div");
            answerPara.classList.add("answerPara");
            answerCont.appendChild(answerPara);
            const answerEl = document.createElement("p");
            answerEl.innerHTML = answer;
            answerPara.appendChild(answerEl);
            card.appendChild(answerCont);
            mainContinerEl.appendChild(card);
        }

        FAQuestions();

        const allCont = mainContinerEl.querySelectorAll(".card");

        allCont.forEach((e) => {
            const btn = e.querySelector(".question");

            btn.addEventListener("click", (pos) => {
                allCont.forEach((item) => {
                    if (item !== e) item.classList.remove("active");
                });
                e.classList.toggle("active");
            });
        });

        function shortenURL() {
            let url = prompt("Enter a URL");
            if (!url) return;

            const validProtocols = ["http://", "https://"];
            const hasValidProtocol = validProtocols.some(protocol => url.startsWith(protocol));

            if (!hasValidProtocol) {
                if (!url.startsWith("http://") && !url.startsWith("https://") &&
                    !url.startsWith("http://www.") && !url.startsWith("https://www.")) {
                    url = "http://" + url;
                }
            }

            const apiUrl = "https://llega.top/?url=" + encodeURIComponent(url);

            fetch(apiUrl)
                .then(response => {
                    if (response.ok) return response.text();
                    else return Promise.reject("API request failed");
                })
                .then(data => {
                    const tempTextArea = document.createElement("textarea");
                    tempTextArea.value = data;
                    document.body.appendChild(tempTextArea);
                    tempTextArea.select();
                    document.execCommand("copy");
                    document.body.removeChild(tempTextArea);

                    const confirmationMessage = `Short URL generated:\n${data}\n\nURL has been copied to clipboard.\nYou can also manually copy it.`;
                    prompt(confirmationMessage, data);
                })
                .catch(() => alert("Something went wrong. Please try again."));
        }
    </script>
</body>

</html>