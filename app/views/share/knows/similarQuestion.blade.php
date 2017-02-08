<h1>看看以下内容是否解决了您的疑问：</h1>
@foreach ($questions as $question)
<div class="title">
	<a href="/knows/question?id={{ $question->questionid }}" target="_blank">{{ $question->title }}</a>
</div>
<div class="answer">
	答: {{ Ca\Common::ubb(Ca\Service\SensitiveService::replace($question->best_answer)) }}
</div>
@endforeach