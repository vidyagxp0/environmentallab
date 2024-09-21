@extends('frontend.layout.main')
@section('container')
<script type="text/javascript">
    window.history.pushState({ page: 1 }, "", "");
    window.addEventListener("popstate", function(event) {
      window.history.pushState({ page: 2 }, "", "");
      alert("You Can not back, please press the Close training button.");
    });

    </script>
    {{-- ======================================
                    HEAD BUTTONS
    ======================================= --}}
    <style>
      #complete-training{
        background: Black !important;
      }
    </style>
    <div id="training-quiz-page">
        <div class="container-fluid">

            <div class="training-head-block">
                <div class="btns">
                    <a href="{{ route('TMS.index') }}"><button style="background: Black">Close Training</button></a>

                </div>
            </div>

            <div class="inner-block question-block">
                <div class="inner-block-content">
                    <header class="header">
                        <div class="left-title">Quiz</div>
                        {{-- <div class="right-title">Total Questions: <span id="tque"></span></div> --}}
                    </header>
                    <div class="content">
                        <div id="result" class="quiz-body">
                            <form name="quizForm" onSubmit="">
                                <fieldset>
                                    <div class="question-data">
                                        <div id="qid"></div>
                                        <div id="question"></div>
                                    </div>

                                    <div class="option-block-container" id="question-options">
                                          
                                    </div>
                                  
                                </fieldset>
                                <div class="quiz-buttons">
                                  <button name="next" id="back-btn">Back</button>
                                  <button type="button" id="submit-btn">Submit</button>
                                    <button type="button" id="next-btn">Next</button>
                                </div>
                                <div id="summary-container" style="display: none;">
                                    <h2>Quiz Summary</h2>
                                    <p id="total-marks"></p>
                                    <ul id="summary"></ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="modal fade" id="trainee-sign">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"> <i class="fa fa-times"></i> </button>

                </div>

                <!-- Modal body -->
                <form action="{{ url('trainingComplete', $document->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="electronic-meaning">Electronic Signature Approved Meaning</label>
                            <select name="electronic-meaning">
                                <option selected>- Please Select -</option>
                                <option value="train-complete">Training Completed</option>
                            </select>
                        </div>
                        <div class="group-input">
                            <label for="username">Username</label>
                            <input type="text" name="email" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password</label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <textarea name="comment"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button>Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var idElement = document.getElementById('qid');
        var questionElement = document.getElementById('question');
var choicesElement = document.getElementById('question-options');
var backBtn = document.getElementById('back-btn');
var nextBtn = document.getElementById('next-btn');
var submitBtn = document.getElementById('submit-btn');
var summaryElement = document.getElementById('summary-container');
var totalMarksElement = document.getElementById('total-marks');

var quizData;
var currentQuestion = 0;
var userAnswers = [];


// fetch("{{ url('example',$document->id) }}")
//   .then(function(response) {
//     return response.json();
//   })
//   .then(function(data) {
//     quizData = data;
//     loadQuestion();
//   })
//   .catch(function(error) {          
//     console.log('Error fetching quiz data:', error);
//   });

  // serve api https

fetch("{{ url()->secure('example', $document->id) }}")
  .then(function(response) {
    return response.json();
  })
  .then(function(data) {
    quizData = data;
    loadQuestion();
  })
  .catch(function(error) {
    console.log('Error fetching quiz data:', error);
  });

function loadQuestion() {
  var question = quizData[currentQuestion]; 
  var questionType = question.type;

  questionElement.textContent = question.question; 
  idElement.textContent = question.id; 
  choicesElement.innerHTML = ''; 

  if (questionType === 'Text Field') {
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'answer';
    input.placeholder = 'Type your answer...';

    choicesElement.appendChild(input);
  } 
  else if (questionType === 'Single Selection Questions') {
    // Single selection (radio buttons)
    for (var i = 0; i < question.choices.length; i++) {
      var li = document.createElement('li');
      var label = document.createElement('label');
      var input = document.createElement('input');
      input.type = 'radio';
      input.name = 'answer';
      input.value = i;
      label.appendChild(input);
      label.appendChild(document.createTextNode(question.choices[i]));
      li.appendChild(label);
      choicesElement.appendChild(li);
    }
  } 
  else if (questionType === 'Multi Selection Questions') {
    // Multi selection (checkboxes)
    for (var i = 0; i < question.choices.length; i++) {
      var li = document.createElement('li');
      var label = document.createElement('label');
      var input = document.createElement('input');
      input.type = 'checkbox';
      input.name = 'answer';
      input.value = i;
      label.appendChild(input);
      label.appendChild(document.createTextNode(question.choices[i]));
      li.appendChild(label);
      choicesElement.appendChild(li);
    }
  }

  // Update navigation buttons (Next/Submit)
  updateButtons(); 
}




function updateButtons() {
  backBtn.disabled = currentQuestion === 0;
  nextBtn.disabled = currentQuestion === quizData.length - 1;
  submitBtn.style.display = currentQuestion === quizData.length - 1 ? 'block' : 'none';
}


function nextQuestion() {
  saveAnswer();
  currentQuestion++;
  loadQuestion();
}

function previousQuestion() {
  saveAnswer();
  currentQuestion--;
  loadQuestion();
}


function displaySummary(marks) {
  // Hide the question UI and show the summary
  questionElement.style.display = 'none';
  choicesElement.style.display = 'none';
  backBtn.style.display = 'none';
  nextBtn.style.display = 'none';
  submitBtn.style.display = 'none';
  summaryElement.style.display = 'block';
  totalMarksElement.textContent = 'Total Marks: ' + marks + '/' + quizData.length;

  // Clear the previous summary content
  var summaryList = document.getElementById('summary');
  summaryList.innerHTML = '';

  // Loop through all the questions to display the summary
  for (var i = 0; i < quizData.length; i++) {
    var li = document.createElement('li');
    var question = quizData[i].question;
    var userAnswer = userAnswers[i];
    var correctAnswer = quizData[i].answer;

    var summaryText = `Question ${i + 1}: ${question} `;

    // For Text Field questions, just show the user's answer, no Correct/Incorrect logic
    if (quizData[i].type === 'Text Field') {
      summaryText += `(Text Field) - Your Answer: ${userAnswer[0]}`;
    } 
    else if (quizData[i].type === 'Single Selection Questions') {
      summaryText += `(Single Selection) - Your Answer: ${quizData[i].choices[userAnswer[0]]}`;
      if (correctAnswer == userAnswer[0]) {
        summaryText += ' - Correct'; // Only show "Correct" if the answer is right
      }
    } 
    else if (quizData[i].type === 'Multi Selection Questions') {
      // Check if the user's selections match the correct answers
      var userSelectedChoices = userAnswer.map(index => quizData[i].choices[index]);
      summaryText += `(Multi Selection) - Your Selections: ${userSelectedChoices.join(', ')}`;
      
      if (arraysEqual(correctAnswer, userAnswer)) {
        summaryText += ' - Correct'; // Only show "Correct" if all selected answers are right
      }
    }

    // Append the summary text to the list item
    li.textContent = summaryText;
    summaryList.appendChild(li);
  }
}




// Function to compare two arrays
function arraysEqual(arr1, arr2) {
  // First, check if both arrays are of the same length
  if (arr1.length !== arr2.length) {
    return false;
  }

  // Sort both arrays before comparing (order may not matter in multiple select questions)
  arr1 = arr1.sort();
  arr2 = arr2.sort();

  // Compare element by element
  for (var i = 0; i < arr1.length; i++) {
    if (arr1[i] !== arr2[i]) {
      return false;
    }
  }

  return true;
}


// Function to save the user's answer
function saveAnswer() {
  var inputs = document.getElementsByName('answer');
  var answer = [];

  if (quizData[currentQuestion].type === 'Text Field') {
    answer.push(inputs[0].value); // Save the text answer
  } 
  else if (quizData[currentQuestion].type === 'Single Selection Questions') {
    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].checked) {
        answer = [i]; // Save selected radio button (single answer)
        break;
      }
    }
  } 
  else if (quizData[currentQuestion].type === 'Multi Selection Questions') {
    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].checked) {
        answer.push(i); // Save selected checkboxes (multiple answers)
      }
    }
  }

  userAnswers[currentQuestion] = answer; // Save answer in the array
  console.log('Saved answer for question ' + currentQuestion, answer);
}

// Function to submit the quiz and check answers
function submitQuiz() {
  saveAnswer(); // Save the last answer
  var marks = 0;

  for (var i = 0; i < quizData.length; i++) {
    var correctAnswer = quizData[i].answer;
    var userAnswer = userAnswers[i];

    if (quizData[i].type === 'Text Field') {
      continue; // For Text Field, we don't check correctness
    } 
    else if (quizData[i].type === 'Single Selection Questions') {
      if (correctAnswer == userAnswer[0]) {
        marks++; 
      }
    } 
    else if (quizData[i].type === 'Multi Selection Questions') {
      if (arraysEqual(correctAnswer, userAnswer)) {
        marks++; 
      }
    }
  }

  displaySummary(marks); 
}






nextBtn.addEventListener('click', nextQuestion);
backBtn.addEventListener('click', previousQuestion);
submitBtn.addEventListener('click', submitQuiz);

    </script>
@endsection
