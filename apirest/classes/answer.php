<?php
class Answer
{
    public $answerid;
    public $ownerid;
    public $creationdate;
	public $score;
    public $information;
    public $reservationsystem;
	public $chosenday;
    public $rehire;
    public $partyroomrecommend;
    public $soundandilluminationrecommend;
    public $crockeryandtablelinenrecommend;
    public $cateringrecommend;
    public $sendoffersbyemail;
    public $suggest;


	public function deleteAnswer()
	{

	}

	public function updateAnswer()
	{

	}

	public function insertAnswer()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				insert into answers 
				(ownerid,score,creationdate,information,reservationsystem,chosenday,rehire,partyroomrecommend,soundandilluminationrecommend,crockeryandtablelinenrecommend,cateringrecommend,sendoffersbyemail,suggest)
				values (:ownerid,:score,:creationdate,:information,:reservationsystem,:chosenday,:rehire,:partyroomrecommend,:soundandilluminationrecommend,:crockeryandtablelinenrecommend,:cateringrecommend,:sendoffersbyemail,:suggest)");
		$consulta->bindValue(':ownerid', $this->ownerid, PDO::PARAM_INT);
        $consulta->bindValue(':score', $this->score, PDO::PARAM_INT);
        $consulta->bindValue(':creationdate', $this->creationdate, PDO::PARAM_STR);
        $consulta->bindValue(':information', $this->information, PDO::PARAM_STR);
        $consulta->bindValue(':reservationsystem', $this->reservationsystem, PDO::PARAM_STR);
		$consulta->bindValue(':chosenday', $this->chosenday, PDO::PARAM_INT);
        $consulta->bindValue(':rehire', $this->rehire, PDO::PARAM_INT);
        $consulta->bindValue(':partyroomrecommend', $this->partyroomrecommend, PDO::PARAM_INT);
        $consulta->bindValue(':soundandilluminationrecommend', $this->soundandilluminationrecommend, PDO::PARAM_INT);
        $consulta->bindValue(':crockeryandtablelinenrecommend', $this->crockeryandtablelinenrecommend, PDO::PARAM_INT);
        $consulta->bindValue(':cateringrecommend', $this->cateringrecommend, PDO::PARAM_INT);
        $consulta->bindValue(':sendoffersbyemail', $this->sendoffersbyemail, PDO::PARAM_INT);
        $consulta->bindValue(':suggest', $this->suggest, PDO::PARAM_INT);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}


}